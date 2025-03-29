import { mount } from "@vue/test-utils";
import { describe, it, expect, vi } from 'vitest';
import TransactionTable from "@/components/organisms/TransactionTable.vue"

describe('TransactionTable', () => {
  it('mostra mensagem quando não há transações', () => {
    const wrapper = mount(TransactionTable, {
      props: {
        title: 'Recent Transactions',
        transactions: [],
        loading: false,
        error: '',
        totalItems: 0,
        currentPage: 1,
        perPage: 10
      }
    })
    
    expect(wrapper.find('tbody tr td').text()).toContain('Sem transações encontradas')
  })
}) 