import { mount } from '@vue/test-utils'
import { nextTick } from 'vue'
import Modal from '../Modal.vue'
import { describe, it, expect, beforeEach, vi } from 'vitest'

// Mock Teleport to make it a pass-through
vi.mock('vue', async () => {
  const actual = await vi.importActual('vue')
  return {
    ...actual,
    Teleport: (props: any, { slots }: any) => slots.default()
  }
})

describe('Modal.vue', () => {
  beforeEach(() => {
    document.body.innerHTML = ''
  })

  it('não renderiza quando modelValue é false', () => {
    const wrapper = mount(Modal, {
      props: {
        modelValue: false
      },
      slots: {
        default: 'Modal Content'
      }
    })
    
    expect(wrapper.find('.fixed.inset-0').exists()).toBe(false)
  })
  
  it('renderiza quando modelValue é true', () => {
    const wrapper = mount(Modal, {
      props: {
        modelValue: true
      },
      slots: {
        default: 'Modal Content'
      }
    })
    
    expect(wrapper.find('.fixed.inset-0').exists()).toBe(true)
    expect(wrapper.text()).toContain('Modal Content')
  })
  
  it('renderiza título quando a prop title é fornecida', () => {
    const wrapper = mount(Modal, {
      props: {
        modelValue: true,
        title: 'Modal Title'
      },
      slots: {
        default: 'Modal Content'
      }
    })
    
    expect(wrapper.text()).toContain('Modal Title')
    expect(wrapper.find('h3').exists()).toBe(true)
    expect(wrapper.find('h3').text()).toBe('Modal Title')
  })
  
  it('emite evento update:modelValue quando o botão de fechar é clicado', async () => {
    const wrapper = mount(Modal, {
      props: {
        modelValue: true,
        title: 'Modal Title'
      },
      slots: {
        default: 'Modal Content'
      }
    })
    
    await wrapper.find('button').trigger('click')
    expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    expect(wrapper.emitted('update:modelValue')[0]).toEqual([false])
  })
  
  it('emite evento update:modelValue quando clicado fora do modal', async () => {
    const wrapper = mount(Modal, {
      props: {
        modelValue: true
      },
      slots: {
        default: 'Modal Content'
      }
    })
    
    await wrapper.find('div.fixed.inset-0.z-50').trigger('click')
    expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    expect(wrapper.emitted('update:modelValue')[0]).toEqual([false])
  })
  
  it('aplica a classe de tamanho correta baseada na prop size', () => {
    const sizes = ['sm', 'md', 'lg', 'xl', '2xl', 'full']
    
    for (const size of sizes) {
      const wrapper = mount(Modal, {
        props: {
          modelValue: true,
          size
        },
        slots: {
          default: 'Modal Content'
        }
      })
      
      switch (size) {
        case 'sm':
          expect(wrapper.find('.sm\\:max-w-sm').exists()).toBe(true)
          break
        case 'md':
          expect(wrapper.find('.sm\\:max-w-md').exists()).toBe(true)
          break
        case 'lg':
          expect(wrapper.find('.sm\\:max-w-lg').exists()).toBe(true)
          break
        case 'xl':
          expect(wrapper.find('.sm\\:max-w-xl').exists()).toBe(true)
          break
        case '2xl':
          expect(wrapper.find('.sm\\:max-w-2xl').exists()).toBe(true)
          break
        case 'full':
          expect(wrapper.find('.sm\\:max-w-full').exists()).toBe(true)
          break
      }
    }
  })
  
  it('renderiza o footer quando o slot footer é fornecido', () => {
    const wrapper = mount(Modal, {
      props: {
        modelValue: true
      },
      slots: {
        default: 'Modal Content',
        footer: 'Modal Footer'
      }
    })
    
    expect(wrapper.text()).toContain('Modal Footer')
    expect(wrapper.find('.bg-gray-50').exists()).toBe(true)
  })
})
