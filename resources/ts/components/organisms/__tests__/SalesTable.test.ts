import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import SalesTable from '@/components/organisms/SalesTable.vue'
import SalesFilter from '@/components/molecules/SalesFilter.vue'

const mockSales = [
  { 
    id: 1, 
    product_name: 'Product A', 
    price: 100, 
    quantity: 2, 
    user_name: 'John Doe',
    category_name: 'Category A',
    amount: 200,
    status: 'success',
    created_at: '2023-01-15'
  },
  { 
    id: 2, 
    product_name: 'Product B', 
    price: 150, 
    quantity: 1,
    user_name: 'Jane Smith',
    category_name: 'Category B',
    amount: 150,
    status: 'warning',
    created_at: '2023-01-16'
  },
]

describe('SalesTable', () => {
  it('emite evento filter-change quando o filtro é alterado', async () => {
    const wrapper = mount(SalesTable, {
      props: {
        title: 'Recent Sales',
        sales: mockSales,
        loading: false,
        error: '',
        totalItems: 2,
        currentPage: 1,
        perPage: 10
      }
    })
    
    const salesFilter = wrapper.findComponent(SalesFilter)
    await salesFilter.vm.$emit('filter-change', { startDate: '2023-01-01', endDate: '2023-01-31' })
    
    expect(wrapper.emitted('filter-change')).toBeTruthy()
    expect(wrapper.emitted('filter-change')[0][0]).toEqual({
      startDate: '2023-01-01',
      endDate: '2023-01-31',
      page: 1,
      per_page: 10
    })
  })
  
  it('altera a ordenação quando um cabeçalho é clicado', async () => {
    const wrapper = mount(SalesTable, {
      props: {
        title: 'Recent Sales',
        sales: mockSales,
        loading: false,
        error: '',
        totalItems: 2,
        currentPage: 1,
        perPage: 10
      }
    })
    
    // Clicar no cabeçalho para ordenar por produto
    await wrapper.findAll('th')[1].trigger('click')
    
    expect(wrapper.emitted('filter-change')).toBeTruthy()
    expect(wrapper.emitted('filter-change')[0][0]).toEqual({
      orderBy: 'product_name',
      orderDirection: 'desc',
      page: 1,
      perPage: 10
    })
    
    // Clicar novamente para inverter a ordem
    await wrapper.findAll('th')[1].trigger('click')
    
    expect(wrapper.emitted('filter-change')[1][0]).toEqual({
      orderBy: 'product_name',
      orderDirection: 'asc',
      page: 1,
      perPage: 10
    })
  })
}) 