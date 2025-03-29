import { mount } from '@vue/test-utils'
import Spinner from '../Spinner.vue'
import { describe, it, expect } from 'vitest'

describe('Spinner.vue', () => {
  it('renderiza corretamente com as props padrão', () => {
    const wrapper = mount(Spinner)
    
    expect(wrapper.find('svg').exists()).toBe(true)
    expect(wrapper.find('svg').classes()).toContain('h-8')
    expect(wrapper.find('svg').classes()).toContain('w-8')
    expect(wrapper.find('svg').classes()).toContain('animate-spin')
    expect(wrapper.find('svg').classes()).toContain('text-purple-600')
  })
  
  it('aplica as classes de tamanho corretas', () => {
    const sizes = ['sm', 'md', 'lg']
    
    for (const size of sizes) {
      const wrapper = mount(Spinner, {
        props: { size }
      })
      
      switch (size) {
        case 'sm':
          expect(wrapper.find('svg').classes()).toContain('h-4')
          expect(wrapper.find('svg').classes()).toContain('w-4')
          break
        case 'md':
          expect(wrapper.find('svg').classes()).toContain('h-8')
          expect(wrapper.find('svg').classes()).toContain('w-8')
          break
        case 'lg':
          expect(wrapper.find('svg').classes()).toContain('h-12')
          expect(wrapper.find('svg').classes()).toContain('w-12')
          break
      }
    }
  })
})
