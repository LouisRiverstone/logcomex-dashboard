import { mount } from '@vue/test-utils'
import StatCard from '../StatCard.vue'
import { describe, it, expect } from 'vitest'

describe('StatCard.vue', () => {
  it('renderiza corretamente com as props básicas', () => {
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00'
      }
    })
    
    expect(wrapper.text()).toContain('Total Vendas')
    expect(wrapper.text()).toContain('R$ 1.500,00')
    expect(wrapper.find('.bg-white').exists()).toBe(true)
    expect(wrapper.find('.rounded-lg').exists()).toBe(true)
    expect(wrapper.find('.shadow-sm').exists()).toBe(true)
  })
  
  it('renderiza o período quando fornecido', () => {
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        period: 'Últimos 30 dias'
      }
    })
    
    expect(wrapper.text()).toContain('Últimos 30 dias')
  })
  
  it('renderiza informações de tendência quando fornecidas', () => {
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        trend: {
          value: 15,
          direction: 'up',
          label: 'vs. mês anterior'
        }
      }
    })
    
    expect(wrapper.text()).toContain('15%')
    expect(wrapper.text()).toContain('vs. mês anterior')
    expect(wrapper.find('.text-green-600').exists()).toBe(true)
  })
  
  it('renderiza tendência negativa corretamente', () => {
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        trend: {
          value: -10,
          direction: 'down',
          label: 'vs. mês anterior'
        }
      }
    })
    
    expect(wrapper.text()).toContain('10%')
    expect(wrapper.find('.text-red-600').exists()).toBe(true)
  })
  
  it('renderiza ícone quando fornecido', () => {
    const iconComponent = {
      template: '<svg></svg>'
    }
    
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        icon: iconComponent,
        color: 'blue'
      }
    })
    
    expect(wrapper.findComponent(iconComponent).exists()).toBe(true)
    expect(wrapper.find('.bg-blue-100').exists()).toBe(true)
    expect(wrapper.find('.text-blue-600').exists()).toBe(true)
  })
  
  it('renderiza o sparkline quando dados são fornecidos', () => {
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        sparklineData: [10, 15, 7, 20, 14, 25],
        color: 'green'
      }
    })
    
    expect(wrapper.find('svg.w-full').exists()).toBe(true)
    expect(wrapper.find('path').attributes('stroke')).toBe('#10b981') // Cor verde
  })
  
  it('não renderiza o sparkline quando não há dados', () => {
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        sparklineData: []
      }
    })
    
    expect(wrapper.find('svg.w-full').exists()).toBe(false)
  })
  
  it('usa as cores padrão quando a cor não é especificada', () => {
    const iconComponent = {
      template: '<svg></svg>'
    }
    
    const wrapper = mount(StatCard, {
      props: {
        title: 'Total Vendas',
        value: 'R$ 1.500,00',
        icon: iconComponent,
        sparklineData: [10, 15, 7, 20, 14, 25]
      }
    })
    
    expect(wrapper.find('.bg-purple-100').exists()).toBe(true)
    expect(wrapper.find('.text-purple-600').exists()).toBe(true)
    expect(wrapper.find('path').attributes('stroke')).toBe('#8b5cf6') // Cor roxa padrão
  })
})
