import { mount } from '@vue/test-utils'
import Card from '../Card.vue'
import { describe, it, expect } from 'vitest'

describe('Card.vue', () => {
  it('renderiza corretamente com as props padrão', () => {
    const wrapper = mount(Card, {
      slots: {
        default: 'Card Content'
      }
    })
    
    expect(wrapper.text()).toBe('Card Content')
    expect(wrapper.classes()).toContain('bg-white')
    expect(wrapper.classes()).toContain('rounded-lg')
    expect(wrapper.classes()).toContain('shadow-sm')
    
    // The p-4 class is on the inner content div (the second div child)
    const contentDiv = wrapper.findAll('div').at(1);
    expect(contentDiv.exists()).toBe(true)
    expect(contentDiv.classes()).toContain('p-4')
  })
  
  it('renderiza header quando o slot header é fornecido', () => {
    const wrapper = mount(Card, {
      slots: {
        header: 'Card Header',
        default: 'Card Content'
      }
    })
    
    expect(wrapper.text()).toContain('Card Header')
    expect(wrapper.text()).toContain('Card Content')
    expect(wrapper.find('.border-b').exists()).toBe(true)
    expect(wrapper.find('.bg-gray-50').exists()).toBe(true)
  })
  
  it('renderiza footer quando o slot footer é fornecido', () => {
    const wrapper = mount(Card, {
      slots: {
        footer: 'Card Footer',
        default: 'Card Content'
      }
    })
    
    expect(wrapper.text()).toContain('Card Footer')
    expect(wrapper.text()).toContain('Card Content')
    expect(wrapper.find('.border-t').exists()).toBe(true)
    expect(wrapper.find('.bg-gray-50').exists()).toBe(true)
  })
  
  it('remove o padding quando padding=false', () => {
    const wrapper = mount(Card, {
      props: {
        padding: false
      },
      slots: {
        default: 'Card Content'
      }
    })
    
    expect(wrapper.find('div > div').classes()).not.toContain('p-4')
  })
  
  it('aplica classes adicionais', () => {
    const wrapper = mount(Card, {
      props: {
        className: 'test-class'
      },
      slots: {
        default: 'Card Content'
      }
    })
    
    expect(wrapper.classes()).toContain('test-class')
  })
})
