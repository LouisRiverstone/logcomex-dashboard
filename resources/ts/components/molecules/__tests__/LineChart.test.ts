import { mount } from '@vue/test-utils'
import LineChart from '../LineChart.vue'
import BaseChart from '../BaseChart.vue'
import { describe, it, expect, vi } from 'vitest'

// Mock BaseChart component
vi.mock('../BaseChart.vue', () => ({
  default: {
    name: 'BaseChart',
    render: () => {},
    props: ['chartConfig', 'className', 'class']
  }
}))

describe('LineChart.vue', () => {
  const mockLabels = ['Jan', 'Feb', 'Mar']
  const mockDatasets = [
    {
      label: 'Sales',
      data: [20, 30, 40],
      borderColor: 'blue',
      backgroundColor: 'rgba(0, 0, 255, 0.1)',
      fill: true,
      tension: 0.3
    }
  ]

  it('renderiza o componente BaseChart corretamente', () => {
    const wrapper = mount(LineChart, {
      props: {
        labels: mockLabels,
        datasets: mockDatasets
      },
      global: {
        stubs: {
          BaseChart: true
        }
      }
    })
    
    expect(wrapper.findComponent({ name: 'BaseChart' }).exists()).toBe(true)
  })
  
  it('passa a configuração correta de gráfico para o BaseChart', () => {
    const wrapper = mount(LineChart, {
      props: {
        labels: mockLabels,
        datasets: mockDatasets,
        title: 'Sales Trend',
        gridLines: false,
        legend: false
      },
      global: {
        stubs: {
          BaseChart: false
        }
      }
    })
    
    const baseChartProps = wrapper.getComponent(BaseChart).props()
    const chartConfig = baseChartProps.chartConfig

    // Verifica tipo do gráfico
    expect(chartConfig.type).toBe('line')
    
    // Verifica dados
    expect(chartConfig.data.labels).toEqual(mockLabels)
    expect(chartConfig.data.datasets[0].label).toBe(mockDatasets[0].label)
    expect(chartConfig.data.datasets[0].data).toEqual(mockDatasets[0].data)
    expect(chartConfig.data.datasets[0].borderColor).toBe(mockDatasets[0].borderColor)
    expect(chartConfig.data.datasets[0].backgroundColor).toBe(mockDatasets[0].backgroundColor)
    expect(chartConfig.data.datasets[0].fill).toBe(mockDatasets[0].fill)
    expect(chartConfig.data.datasets[0].tension).toBe(mockDatasets[0].tension)
    
    // Verifica opções
    expect(chartConfig.options.plugins.title.display).toBe(true)
    expect(chartConfig.options.plugins.title.text).toBe('Sales Trend')
    expect(chartConfig.options.plugins.legend.display).toBe(false)
    expect(chartConfig.options.scales.x.grid.display).toBe(false)
    expect(chartConfig.options.scales.y.grid.display).toBe(false)
  })
  
  it('usa valores padrão quando propriedades específicas do dataset não são fornecidas', () => {
    const basicDatasets = [
      {
        label: 'Basic Dataset',
        data: [10, 20, 30]
      }
    ]
    
    const wrapper = mount(LineChart, {
      props: {
        labels: mockLabels,
        datasets: basicDatasets
      }
    })
    
    const baseChartProps = wrapper.getComponent(BaseChart).props()
    const chartConfig = baseChartProps.chartConfig
    
    // Verifica valores padrão
    expect(chartConfig.data.datasets[0].borderColor).toBe('#8b5cf6')
    expect(chartConfig.data.datasets[0].backgroundColor).toBe('rgba(139, 92, 246, 0.1)')
    expect(chartConfig.data.datasets[0].tension).toBe(0.4)
    expect(chartConfig.data.datasets[0].fill).toBe(false)
  })
  
  it('aplica classes adicionais corretamente', () => {
    const wrapper = mount(LineChart, {
      props: {
        labels: mockLabels,
        datasets: mockDatasets,
        className: 'test-class'
      }
    })
    
    // Check if one of these props has the expected class
    const props = wrapper.findComponent(BaseChart).props();
    const hasCorrectClass = props.class === 'test-class' || props.className === 'test-class';
    expect(hasCorrectClass).toBe(true);
  })
})
