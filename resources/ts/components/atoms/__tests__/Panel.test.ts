import { mount } from '@vue/test-utils'
import Panel from '../Panel.vue'
import { describe, it, expect } from 'vitest'

describe('Panel.vue', () => {
  it('renderiza corretamente com as props padrão', () => {
    const wrapper = mount(Panel, {
      slots: {
        default: 'Panel Content'
      }
    })
    
    expect(wrapper.text()).toBe('Panel Content')
    expect(wrapper.classes()).toContain('bg-white')
    expect(wrapper.classes()).toContain('rounded-lg')
    expect(wrapper.classes()).toContain('shadow-sm')
    
    const divs = wrapper.findAll('div');
    const contentDiv = divs[divs.length - 1];
    expect(contentDiv.classes()).toContain('p-4')
  })
  
  it('renderiza título quando a prop title é fornecida', () => {
    const wrapper = mount(Panel, {
      props: {
        title: 'Panel Title'
      },
      slots: {
        default: 'Panel Content'
      }
    })
    
    expect(wrapper.text()).toContain('Panel Title')
    expect(wrapper.text()).toContain('Panel Content')
    expect(wrapper.find('h3').exists()).toBe(true)
    expect(wrapper.find('h3').text()).toBe('Panel Title')
  })
  
  it('renderiza ações quando o slot actions é fornecido', () => {
    const wrapper = mount(Panel, {
      slots: {
        actions: 'Panel Actions',
        default: 'Panel Content'
      }
    })
    
    expect(wrapper.text()).toContain('Panel Actions')
    expect(wrapper.text()).toContain('Panel Content')
    expect(wrapper.find('.border-b').exists()).toBe(true)
  })
  
  it('aplica classe contentClass para o conteúdo', () => {
    const wrapper = mount(Panel, {
      props: {
        contentClass: 'custom-content-class'
      },
      slots: {
        default: 'Panel Content'
      }
    })
    
    const divs = wrapper.findAll('div');
    const contentDiv = divs[divs.length - 1];
    expect(contentDiv.classes()).toContain('custom-content-class')
  })
  
  it('aplica classes adicionais', () => {
    const wrapper = mount(Panel, {
      props: {
        className: 'test-class'
      },
      slots: {
        default: 'Panel Content'
      }
    })
    
    expect(wrapper.classes()).toContain('test-class')
  })
})
