import { mount } from '@vue/test-utils'
import { nextTick } from 'vue'
import BaseChart from '../BaseChart.vue'
import { describe, it, expect, vi, beforeEach } from 'vitest'

// Mock variables for TypeScript
declare global {
  var mockDestroy: ReturnType<typeof vi.fn>;
  var mockUpdate: ReturnType<typeof vi.fn>;
  var Chart: ReturnType<typeof vi.fn>;
}

// Mock Chart.js before importing the component
vi.mock('chart.js/auto', () => {
  const mockDestroy = vi.fn();
  const mockUpdate = vi.fn();
  
  class MockChart {
    destroy = mockDestroy;
    update = mockUpdate;
  }
  
  const Chart = vi.fn().mockImplementation((element, config) => {
    return new MockChart();
  });
  
  // Expose these to global scope
  global.mockDestroy = mockDestroy;
  global.mockUpdate = mockUpdate;
  global.Chart = Chart;
  
  return {
    default: Chart
  };
});

describe('BaseChart.vue', () => {
  const mockChartConfig = {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar'],
      datasets: [{
        label: 'Sales',
        data: [20, 30, 40],
        backgroundColor: 'red'
      }]
    },
    options: {
      responsive: true
    }
  }
  
  beforeEach(() => {
    vi.clearAllMocks()
  })
  
  it('renderiza corretamente com a configuração básica', () => {
    const wrapper = mount(BaseChart, {
      props: {
        chartConfig: mockChartConfig
      }
    })
    
    expect(wrapper.find('canvas').exists()).toBe(true)
    expect(wrapper.classes()).toContain('w-full')
    expect(wrapper.classes()).toContain('h-full')
  })
  
  it('inicializa o Chart.js na montagem do componente', () => {
    mount(BaseChart, {
      props: {
        chartConfig: mockChartConfig
      }
    })
    
    expect(Chart).toHaveBeenCalledTimes(1)
    expect(Chart).toHaveBeenCalledWith(expect.any(HTMLCanvasElement), mockChartConfig)
  })
  
  it('destrói o Chart.js na desmontagem do componente', () => {
    const wrapper = mount(BaseChart, {
      props: {
        chartConfig: mockChartConfig
      }
    })
    
    wrapper.unmount()
    
    expect(mockDestroy).toHaveBeenCalledTimes(1)
  })
  
  it('recria o Chart.js quando a configuração muda', async () => {
    const wrapper = mount(BaseChart, {
      props: {
        chartConfig: mockChartConfig
      }
    })
    
    expect(Chart).toHaveBeenCalledTimes(1)
    
    // Mudar a configuração do gráfico
    const newConfig = {
      ...mockChartConfig,
      type: 'line'
    }
    
    await wrapper.setProps({ chartConfig: newConfig })
    
    // A instância anterior deve ser destruída
    expect(mockDestroy).toHaveBeenCalledTimes(1)
    
    // Uma nova instância deve ser criada
    expect(Chart).toHaveBeenCalledTimes(2)
    expect(Chart).toHaveBeenLastCalledWith(expect.any(HTMLCanvasElement), newConfig)
  })
  
  it('expõe métodos updateChart para atualização externa', async () => {
    const wrapper = mount(BaseChart, {
      props: {
        chartConfig: mockChartConfig
      }
    })
    
    // Acessar método exposto
    const { updateChart } = wrapper.vm as any
    updateChart()
    
    expect(mockUpdate).toHaveBeenCalledTimes(1)
  })
  
  it('aplica classes adicionais corretamente', () => {
    const wrapper = mount(BaseChart, {
      props: {
        chartConfig: mockChartConfig,
        className: 'test-class'
      }
    })
    
    expect(wrapper.classes()).toContain('test-class')
  })
})
