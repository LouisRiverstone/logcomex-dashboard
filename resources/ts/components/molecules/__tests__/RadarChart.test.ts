import { mount } from '@vue/test-utils'
import RadarChart from '../RadarChart.vue'
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

describe('RadarChart.vue', () => {
  const defaultProps = {
    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
    datasets: [
      {
        label: 'Sales 2022',
        data: [65, 59, 90, 81],
        backgroundColor: 'rgba(139, 92, 246, 0.2)',
        borderColor: '#8b5cf6'
      },
      {
        label: 'Sales 2023',
        data: [28, 48, 40, 19],
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        borderColor: '#3b82f6'
      }
    ]
  }
  
  it('renderiza o BaseChart com a configuração correta', () => {
    const wrapper = mount(RadarChart, {
      props: defaultProps
    })
    
    expect(wrapper.findComponent(BaseChart).exists()).toBe(true)
    
    // Verificar se as propriedades corretas foram passadas para o BaseChart
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.type).toBe('radar')
    expect(baseChartProps.chartConfig.data.labels).toEqual(defaultProps.labels)
    expect(baseChartProps.chartConfig.data.datasets).toHaveLength(2)
    expect(baseChartProps.chartConfig.data.datasets[0].label).toBe('Sales 2022')
    expect(baseChartProps.chartConfig.data.datasets[0].data).toEqual([65, 59, 90, 81])
  })
  
  it('aplica valores padrão para cores quando não especificados', () => {
    const propsWithoutColors = {
      labels: ['Q1', 'Q2', 'Q3', 'Q4'],
      datasets: [
        {
          label: 'Sales',
          data: [65, 59, 90, 81]
        }
      ]
    }
    
    const wrapper = mount(RadarChart, {
      props: propsWithoutColors
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    const dataset = baseChartProps.chartConfig.data.datasets[0]
    
    expect(dataset.backgroundColor).toBe('rgba(139, 92, 246, 0.2)')
    expect(dataset.borderColor).toBe('#8b5cf6')
    expect(dataset.pointBackgroundColor).toBe('#8b5cf6')
    expect(dataset.pointBorderColor).toBe('#fff')
  })
  
  it('passa a propriedade className para o BaseChart', () => {
    const wrapper = mount(RadarChart, {
      props: {
        ...defaultProps,
        className: 'custom-chart-class'
      }
    })
    
    // Check if one of these props has the expected class
    const props = wrapper.findComponent(BaseChart).props();
    const hasCorrectClass = props.class === 'custom-chart-class' || props.className === 'custom-chart-class';
    expect(hasCorrectClass).toBe(true);
  })
  
  it('configura o título do gráfico quando fornecido', () => {
    const wrapper = mount(RadarChart, {
      props: {
        ...defaultProps,
        title: 'Quarterly Sales Comparison'
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.title.display).toBe(true)
    expect(baseChartProps.chartConfig.options.plugins.title.text).toBe('Quarterly Sales Comparison')
  })
  
  it('desativa as linhas de grade quando gridLines é falso', () => {
    const wrapper = mount(RadarChart, {
      props: {
        ...defaultProps,
        gridLines: false
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.scales.r.angleLines.display).toBe(false)
    expect(baseChartProps.chartConfig.options.scales.r.grid.display).toBe(false)
  })
  
  it('desativa a legenda quando legend é falso', () => {
    const wrapper = mount(RadarChart, {
      props: {
        ...defaultProps,
        legend: false
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.legend.display).toBe(false)
  })
}) 