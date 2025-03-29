import { mount } from '@vue/test-utils'
import FilterBar from '../FilterBar.vue'
import { describe, it, expect, beforeEach } from 'vitest'

describe('FilterBar.vue', () => {
  it('renderiza corretamente com as props padrão', () => {
    const wrapper = mount(FilterBar)
    
    expect(wrapper.find('select').exists()).toBe(true)
    expect(wrapper.find('select').element.value).toBe('30d')
    expect(wrapper.findAll('option').length).toBe(5)
    expect(wrapper.find('input[type="date"]').exists()).toBe(false)
  })
  
  it('mostra campos de data quando o período "custom" é selecionado', async () => {
    const wrapper = mount(FilterBar)
    
    // Inicialmente os campos de data não estão visíveis
    expect(wrapper.find('input[type="date"]').exists()).toBe(false)
    
    // Selecionar o período personalizado
    await wrapper.find('select').setValue('custom')
    
    // Agora os campos de data devem estar visíveis
    expect(wrapper.findAll('input[type="date"]').length).toBe(2)
  })
  
  it('aplica o período padrão passado como prop', () => {
    const wrapper = mount(FilterBar, {
      props: {
        defaultPeriod: '90d'
      }
    })
    
    expect(wrapper.find('select').element.value).toBe('90d')
  })
  
  it('emite evento filter-change quando o período é alterado', async () => {
    const wrapper = mount(FilterBar)
    
    await wrapper.find('select').setValue('7d')
    
    expect(wrapper.emitted()).toHaveProperty('filter-change')
    expect(wrapper.emitted('filter-change')[0][0]).toEqual({
      period: '7d',
      startDate: '',
      endDate: ''
    })
  })
  
  it('emite evento filter-change quando as datas são definidas no modo personalizado', async () => {
    const wrapper = mount(FilterBar)
    
    // Selecionar o período personalizado
    await wrapper.find('select').setValue('custom')
    
    const [startDateInput, endDateInput] = wrapper.findAll('input[type="date"]')
    
    // Definir as datas
    await startDateInput.setValue('2023-01-01')
    await endDateInput.setValue('2023-01-31')
    
    // Verificar se o evento foi emitido com os valores corretos
    const emittedEvents = wrapper.emitted('filter-change')
    expect(emittedEvents).toHaveLength(3) // Uma vez para o período, e uma para cada data
    
    // Último evento deve ter as datas definidas
    expect(emittedEvents[2][0]).toEqual({
      period: 'custom',
      startDate: '2023-01-01',
      endDate: '2023-01-31'
    })
  })
  
  it('renderiza corretamente o slot de ações', () => {
    const wrapper = mount(FilterBar, {
      slots: {
        actions: '<button>Export</button>'
      }
    })
    
    expect(wrapper.find('button').exists()).toBe(true)
    expect(wrapper.find('button').text()).toBe('Export')
  })
}) 