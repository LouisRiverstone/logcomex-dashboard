import { mount } from '@vue/test-utils'
import Badge from '../Badge.vue'
import { describe, it, expect } from 'vitest'

describe('Badge.vue', () => {
  it('renderiza corretamente com as props padrão', () => {
    const wrapper = mount(Badge, {
      slots: {
        default: 'Test Badge'
      }
    })
    
    expect(wrapper.text()).toBe('Test Badge')
    expect(wrapper.classes()).toContain('bg-purple-100')
    expect(wrapper.classes()).toContain('text-purple-800')
  })
  
  it('aplica a classe correta para cada variante', () => {
    const variants = ['primary', 'secondary', 'success', 'warning', 'danger', 'info']
    
    for (const variant of variants) {
      const wrapper = mount(Badge, {
        props: { variant },
        slots: {
          default: 'Test Badge'
        }
      })
      
      switch (variant) {
        case 'primary':
          expect(wrapper.classes()).toContain('bg-purple-100')
          expect(wrapper.classes()).toContain('text-purple-800')
          break
        case 'secondary':
          expect(wrapper.classes()).toContain('bg-gray-100')
          expect(wrapper.classes()).toContain('text-gray-800')
          break
        case 'success':
          expect(wrapper.classes()).toContain('bg-green-100')
          expect(wrapper.classes()).toContain('text-green-800')
          break
        case 'warning':
          expect(wrapper.classes()).toContain('bg-yellow-100')
          expect(wrapper.classes()).toContain('text-yellow-800')
          break
        case 'danger':
          expect(wrapper.classes()).toContain('bg-red-100')
          expect(wrapper.classes()).toContain('text-red-800')
          break
        case 'info':
          expect(wrapper.classes()).toContain('bg-blue-100')
          expect(wrapper.classes()).toContain('text-blue-800')
          break
      }
    }
  })
  
  it('aceita e aplica classes adicionais', () => {
    const wrapper = mount(Badge, {
      props: {
        className: 'test-class'
      },
      slots: {
        default: 'Test Badge'
      }
    })
    
    expect(wrapper.classes()).toContain('test-class')
  })
})
