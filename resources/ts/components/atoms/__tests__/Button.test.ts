import { mount } from '@vue/test-utils'
import Button from '../Button.vue'
import { describe, it, expect } from 'vitest'

describe('Button.vue', () => {
  it('renderiza corretamente com as props padrão', () => {
    const wrapper = mount(Button, {
      slots: {
        default: 'Test Button'
      }
    })
    
    expect(wrapper.text()).toBe('Test Button')
    expect(wrapper.attributes('type')).toBe('button')
    expect(wrapper.classes()).toContain('bg-purple-600')
    expect(wrapper.classes()).toContain('text-white')
  })
  
  it('aplica as classes corretas para cada variante', () => {
    const variants = ['primary', 'secondary', 'outline', 'ghost', 'danger']
    
    for (const variant of variants) {
      const wrapper = mount(Button, {
        props: { variant },
        slots: {
          default: 'Test Button'
        }
      })
      
      switch (variant) {
        case 'primary':
          expect(wrapper.classes()).toContain('bg-purple-600')
          expect(wrapper.classes()).toContain('text-white')
          break
        case 'secondary':
          expect(wrapper.classes()).toContain('bg-purple-100')
          expect(wrapper.classes()).toContain('text-purple-800')
          break
        case 'outline':
          expect(wrapper.classes()).toContain('border-purple-600')
          expect(wrapper.classes()).toContain('text-purple-600')
          break
        case 'ghost':
          expect(wrapper.classes()).toContain('text-purple-600')
          break
        case 'danger':
          expect(wrapper.classes()).toContain('bg-red-600')
          expect(wrapper.classes()).toContain('text-white')
          break
      }
    }
  })
  
  it('aplica as classes corretas para cada tamanho', () => {
    const sizes = ['sm', 'md', 'lg']
    
    for (const size of sizes) {
      const wrapper = mount(Button, {
        props: { size },
        slots: {
          default: 'Test Button'
        }
      })
      
      switch (size) {
        case 'sm':
          expect(wrapper.classes()).toContain('text-xs')
          expect(wrapper.classes()).toContain('px-2.5')
          expect(wrapper.classes()).toContain('py-1.5')
          break
        case 'md':
          expect(wrapper.classes()).toContain('text-sm')
          expect(wrapper.classes()).toContain('px-3')
          expect(wrapper.classes()).toContain('py-2')
          break
        case 'lg':
          expect(wrapper.classes()).toContain('text-base')
          expect(wrapper.classes()).toContain('px-4')
          expect(wrapper.classes()).toContain('py-3')
          break
      }
    }
  })
  
  it('emite evento de clique quando não está desativado', async () => {
    const wrapper = mount(Button, {
      slots: {
        default: 'Test Button'
      }
    })
    
    await wrapper.trigger('click')
    expect(wrapper.emitted()).toHaveProperty('click')
  })
  
  it('não emite evento de clique quando está desativado', async () => {
    const wrapper = mount(Button, {
      props: {
        disabled: true
      },
      slots: {
        default: 'Test Button'
      }
    })
    
    expect(wrapper.attributes('disabled')).toBeDefined()
    expect(wrapper.classes()).toContain('opacity-50')
    expect(wrapper.classes()).toContain('cursor-not-allowed')
    
    await wrapper.trigger('click')
    expect(wrapper.emitted().click).toBeUndefined()
  })
})
