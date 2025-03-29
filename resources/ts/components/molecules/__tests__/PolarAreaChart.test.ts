import { mount } from '@vue/test-utils'
import PolarAreaChart from '../PolarAreaChart.vue'
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

describe('PolarAreaChart.vue', () => {
  const defaultProps = {
    labels: ['Produto A', 'Produto B', 'Produto C', 'Produto D'],
    data: [300, 150, 100, 50]
  }
  
  it('renderiza o BaseChart com a configuração correta', () => {
    const wrapper = mount(PolarAreaChart, {
      props: defaultProps
    })
    
    expect(wrapper.findComponent(BaseChart).exists()).toBe(true)
    
    // Verificar se as propriedades corretas foram passadas para o BaseChart
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.type).toBe('polarArea')
    expect(baseChartProps.chartConfig.data.labels).toEqual(defaultProps.labels)
    expect(baseChartProps.chartConfig.data.datasets).toHaveLength(1)
    expect(baseChartProps.chartConfig.data.datasets[0].data).toEqual(defaultProps.data)
  })
  
  it('usa as cores padrão dos backgrounds quando não especificadas', () => {
    const wrapper = mount(PolarAreaChart, {
      props: defaultProps
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    const dataset = baseChartProps.chartConfig.data.datasets[0]
    
    // Verifica se as cores padrão são usadas
    expect(dataset.backgroundColor).toEqual([
      'rgba(139, 92, 246, 0.5)',
      'rgba(167, 139, 250, 0.5)',
      'rgba(196, 181, 253, 0.5)',
      'rgba(221, 214, 254, 0.5)',
      'rgba(237, 233, 254, 0.5)',
      'rgba(245, 243, 255, 0.5)'
    ])
  })
  
  it('usa cores personalizadas quando especificadas', () => {
    const customColors = {
      backgroundColor: ['red', 'green', 'blue', 'yellow'],
      borderColor: ['darkred', 'darkgreen', 'darkblue', 'orange']
    }
    
    const wrapper = mount(PolarAreaChart, {
      props: {
        ...defaultProps,
        ...customColors
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    const dataset = baseChartProps.chartConfig.data.datasets[0]
    
    expect(dataset.backgroundColor).toEqual(customColors.backgroundColor)
    expect(dataset.borderColor).toEqual(customColors.borderColor)
  })
  
  it('preenche as cores extras quando há mais dados que cores', () => {
    const propsMoreData = {
      labels: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
      data: [10, 20, 30, 40, 50, 60, 70, 80],
      backgroundColor: ['red', 'green'],
      borderColor: ['darkred', 'darkgreen']
    }
    
    const wrapper = mount(PolarAreaChart, {
      props: propsMoreData
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    const dataset = baseChartProps.chartConfig.data.datasets[0]
    
    // Deve ter 8 cores para corresponder aos 8 itens de dados
    expect(dataset.backgroundColor).toHaveLength(8)
    expect(dataset.borderColor).toHaveLength(8)
    
    // As duas primeiras cores devem ser as especificadas
    expect(dataset.backgroundColor[0]).toBe('red')
    expect(dataset.backgroundColor[1]).toBe('green')
    
    // As cores restantes devem ser preenchidas com o padrão
    expect(dataset.backgroundColor[2]).toBe('rgba(139, 92, 246, 0.5)')
  })
  
  it('aplica a configuração de título corretamente', () => {
    const wrapper = mount(PolarAreaChart, {
      props: {
        ...defaultProps,
        title: 'Distribuição de Vendas'
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.title.display).toBe(true)
    expect(baseChartProps.chartConfig.options.plugins.title.text).toBe('Distribuição de Vendas')
  })
  
  it('oculta a legenda quando legend é falso', () => {
    const wrapper = mount(PolarAreaChart, {
      props: {
        ...defaultProps,
        legend: false
      }
    })
    
    const baseChartProps = wrapper.findComponent(BaseChart).props()
    
    expect(baseChartProps.chartConfig.options.plugins.legend.display).toBe(false)
  })
  
  it('passa a propriedade className para o BaseChart', () => {
    const wrapper = mount(PolarAreaChart, {
      props: {
        ...defaultProps,
        className: 'my-custom-chart-class'
      }
    })
    
    // Check if one of these props has the expected class
    const props = wrapper.findComponent(BaseChart).props();
    const hasCorrectClass = props.class === 'my-custom-chart-class' || props.className === 'my-custom-chart-class';
    expect(hasCorrectClass).toBe(true);
  })
}) 