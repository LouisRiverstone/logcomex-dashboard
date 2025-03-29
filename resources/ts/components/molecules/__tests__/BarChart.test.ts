import { mount } from '@vue/test-utils'
import BarChart from '../BarChart.vue'
import BaseChart from '../BaseChart.vue'
import { describe, it, expect, vi } from 'vitest'

// Mock BaseChart component
vi.mock('../BaseChart.vue', () => ({
  default: {
    name: 'BaseChart',
    props: ['chartConfig', 'className', 'class'],
    template: '<div class="mock-base-chart"></div>'
  }
}))

describe('BarChart.vue', () => {
  const mockLabels = ['Jan', 'Feb', 'Mar']
  const mockDatasets = [
    {
      label: 'Sales',
      data: [20, 30, 40],
      backgroundColor: 'red'
    }
  ]

  it('renderiza o componente BaseChart corretamente', () => {
    const wrapper = mount(BarChart, {
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
    const wrapper = mount(BarChart, {
      props: {
        labels: mockLabels,
        datasets: mockDatasets,
        title: 'Sales Chart',
        horizontal: true,
        gridLines: false,
        legend: false,
        stacked: true
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
    expect(chartConfig.type).toBe('bar')
    
    // Verifica dados
    expect(chartConfig.data.labels).toEqual(mockLabels)
    expect(chartConfig.data.datasets[0].label).toBe(mockDatasets[0].label)
    expect(chartConfig.data.datasets[0].data).toEqual(mockDatasets[0].data)
    expect(chartConfig.data.datasets[0].backgroundColor).toBe(mockDatasets[0].backgroundColor)
    
    // Verifica opções
    expect(chartConfig.options.indexAxis).toBe('y') // horizontal = true
    expect(chartConfig.options.plugins.title.display).toBe(true)
    expect(chartConfig.options.plugins.title.text).toBe('Sales Chart')
    expect(chartConfig.options.plugins.legend.display).toBe(false)
    expect(chartConfig.options.scales.x.stacked).toBe(true)
    expect(chartConfig.options.scales.y.stacked).toBe(true)
    expect(chartConfig.options.scales.x.grid.display).toBe(false)
    expect(chartConfig.options.scales.y.grid.display).toBe(false)
  })
  
  it('aplica classes adicionais corretamente', () => {
    const wrapper = mount(BarChart, {
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
