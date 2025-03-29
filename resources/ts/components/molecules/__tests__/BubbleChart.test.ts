import { mount } from '@vue/test-utils'
import BubbleChart from '../BubbleChart.vue'
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

describe('BubbleChart.vue', () => {
  const defaultProps = {
    datasets: [
      {
        label: 'Dataset 1',
        data: [
          { x: 10, y: 20, r: 5 },
          { x: 15, y: 10, r: 10 },
          { x: 20, y: 30, r: 15 }
        ],
        backgroundColor: 'rgba(139, 92, 246, 0.7)'
      },
      {
        label: 'Dataset 2',
        data: [
          { x: 5, y: 15, r: 8 },
          { x: 25, y: 5, r: 12 },
          { x: 30, y: 25, r: 6 }
        ],
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 2
      }
    ]
  }
  
  it('renderiza o BaseChart com a configuração correta', () => {
    const wrapper = mount(BubbleChart, {
      props: defaultProps
    })
    
    expect(wrapper.findComponent(BaseChart).exists()).toBe(true)
    
    // Verificar se as propriedades corretas foram passadas para o BaseChart
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.type).toBe('bubble')
    expect(baseChartProps.chartConfig.data.datasets).toHaveLength(2)
    expect(baseChartProps.chartConfig.data.datasets[0].label).toBe('Dataset 1')
    expect(baseChartProps.chartConfig.data.datasets[0].data).toEqual([
      { x: 10, y: 20, r: 5 },
      { x: 15, y: 10, r: 10 },
      { x: 20, y: 30, r: 15 }
    ])
  })
  
  it('aplica os valores padrão para cores quando não especificados', () => {
    const propsWithoutColors = {
      datasets: [
        {
          label: 'Dataset sem cores',
          data: [
            { x: 10, y: 20, r: 5 },
            { x: 15, y: 10, r: 10 }
          ]
        }
      ]
    }
    
    const wrapper = mount(BubbleChart, {
      props: propsWithoutColors
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    const dataset = baseChartProps.chartConfig.data.datasets[0]
    
    expect(dataset.backgroundColor).toBe('rgba(139, 92, 246, 0.7)')
    expect(dataset.borderColor).toBe('rgba(139, 92, 246, 1)')
    expect(dataset.borderWidth).toBe(1)
  })
  
  it('utiliza valores personalizados quando especificados', () => {
    const wrapper = mount(BubbleChart, {
      props: defaultProps
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    const dataset = baseChartProps.chartConfig.data.datasets[1]
    
    expect(dataset.backgroundColor).toBe('rgba(59, 130, 246, 0.7)')
    expect(dataset.borderColor).toBe('rgba(59, 130, 246, 1)')
    expect(dataset.borderWidth).toBe(2)
  })
  
  it('configura o título do gráfico quando fornecido', () => {
    const wrapper = mount(BubbleChart, {
      props: {
        ...defaultProps,
        title: 'Gráfico de Bolhas'
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.title.display).toBe(true)
    expect(baseChartProps.chartConfig.options.plugins.title.text).toBe('Gráfico de Bolhas')
  })
  
  it('não exibe o título do gráfico quando não fornecido', () => {
    const wrapper = mount(BubbleChart, {
      props: defaultProps
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.title.display).toBe(false)
  })
  
  it('desativa as linhas de grade quando gridLines é falso', () => {
    const wrapper = mount(BubbleChart, {
      props: {
        ...defaultProps,
        gridLines: false
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.scales.x.grid.display).toBe(false)
    expect(baseChartProps.chartConfig.options.scales.y.grid.display).toBe(false)
  })
  
  it('desativa a legenda quando legend é falso', () => {
    const wrapper = mount(BubbleChart, {
      props: {
        ...defaultProps,
        legend: false
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.legend.display).toBe(false)
  })
  
  it('passa a propriedade className para o BaseChart', () => {
    const wrapper = mount(BubbleChart, {
      props: {
        ...defaultProps,
        className: 'bubble-chart-custom'
      }
    })
    
    // Check if one of these props has the expected class
    const props = wrapper.findComponent(BaseChart).props();
    const hasCorrectClass = props.class === 'bubble-chart-custom' || props.className === 'bubble-chart-custom';
    expect(hasCorrectClass).toBe(true);
  })
}) 