import { mount } from '@vue/test-utils'
import PieChart from '../PieChart.vue'
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

describe('PieChart.vue', () => {
  const mockLabels = ['Category A', 'Category B', 'Category C']
  const mockData = [300, 200, 100]
  const mockBackgroundColors = ['#ff6384', '#36a2eb', '#ffce56']

  it('renderiza o componente BaseChart corretamente', () => {
    const wrapper = mount(PieChart, {
      props: {
        labels: mockLabels,
        data: mockData
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
    const wrapper = mount(PieChart, {
      props: {
        labels: mockLabels,
        data: mockData,
        backgroundColor: mockBackgroundColors,
        title: 'Sales Distribution',
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
    expect(chartConfig.type).toBe('pie')
    
    // Verifica dados
    expect(chartConfig.data.labels).toEqual(mockLabels)
    expect(chartConfig.data.datasets[0].data).toEqual(mockData)
    expect(chartConfig.data.datasets[0].backgroundColor).toEqual(mockBackgroundColors)
    
    // Verifica opções
    expect(chartConfig.options.plugins.title.display).toBe(true)
    expect(chartConfig.options.plugins.title.text).toBe('Sales Distribution')
    expect(chartConfig.options.plugins.legend.display).toBe(false)
  })
  
  it('usa cores padrão quando não fornecidas', () => {
    const wrapper = mount(PieChart, {
      props: {
        labels: mockLabels,
        data: mockData
      }
    })
    
    const baseChartProps = wrapper.getComponent(BaseChart).props()
    const chartConfig = baseChartProps.chartConfig
    
    // Verifica cores padrão
    expect(chartConfig.data.datasets[0].backgroundColor).toEqual(['#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe', '#f5f3ff'])
  })
  
  it('substitui cores ausentes com a padrão quando há mais dados que cores', () => {
    const wrapper = mount(PieChart, {
      props: {
        labels: ['A', 'B', 'C', 'D', 'E'],
        data: [10, 20, 30, 40, 50],
        backgroundColor: ['#ff0000', '#00ff00'] // Apenas 2 cores para 5 dados
      }
    })
    
    const baseChartProps = wrapper.getComponent(BaseChart).props()
    const chartConfig = baseChartProps.chartConfig
    
    // Deve ter 5 cores com as 2 primeiras especificadas e as 3 restantes preenchidas
    expect(chartConfig.data.datasets[0].backgroundColor.length).toBe(5)
    expect(chartConfig.data.datasets[0].backgroundColor[0]).toBe('#ff0000')
    expect(chartConfig.data.datasets[0].backgroundColor[1]).toBe('#00ff00')
    expect(chartConfig.data.datasets[0].backgroundColor[2]).toBe('#8b5cf6')
  })
  
  it('configura corretamente o tooltip de porcentagem', () => {
    const wrapper = mount(PieChart, {
      props: {
        labels: mockLabels,
        data: mockData
      }
    })
    
    const baseChartProps = wrapper.getComponent(BaseChart).props()
    const chartConfig = baseChartProps.chartConfig
    
    // Verifica se o callback do tooltip está configurado
    expect(chartConfig.options.plugins.tooltip.callbacks.label).toBeDefined()
    expect(typeof chartConfig.options.plugins.tooltip.callbacks.label).toBe('function')
    
    // Teste básico do callback 
    const mockContext = {
      label: 'Test Label',
      formattedValue: '100',
      raw: 50,
      dataset: {
        data: [50, 50] // Total 100
      }
    }
    
    const result = chartConfig.options.plugins.tooltip.callbacks.label(mockContext)
    expect(result).toBe('Test Label: 100 (50%)')
  })
  
  it('aplica classes adicionais corretamente', () => {
    const wrapper = mount(PieChart, {
      props: {
        labels: mockLabels,
        data: mockData,
        className: 'test-class'
      }
    })
    
    // Check if one of these props has the expected class
    const props = wrapper.findComponent(BaseChart).props();
    const hasCorrectClass = props.class === 'test-class' || props.className === 'test-class';
    expect(hasCorrectClass).toBe(true);
  })
})
