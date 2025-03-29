<template>
  <div class="space-y-4 mb-4">
    <!-- Filtros básicos -->
    <div class="flex flex-wrap gap-2 items-center justify-between">
      <div class="flex flex-wrap gap-2 items-center">
        <div class="flex flex-row gap-2 items-center">
          <input
            v-model="startDate"
            type="date"
            class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
            @blur="onFilterChange"
          />
          <span class="text-xs text-gray-500">até</span>
          <input
            v-model="endDate"
            type="date"
            class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
            @blur="onFilterChange"
          />
        </div>

        <input
          v-model="customer"
          placeholder="Nome do Cliente"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @input="debounceSearch"
        />

        <select
          v-model="selectedStatus"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @change="onFilterChange"
        >
          <option :value="null">Todos</option>
          <option value="success">Sucesso</option>
          <option value="warning">Aguardando</option>
          <option value="danger">Falha</option>
        </select>

        <select
          v-model="selectedType"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @change="onFilterChange"
        >
          <option :value="null">Todos</option>
          <option value="sale">Venda</option>
          <option value="refund">Reembolso</option>
          <option value="subscription">Assinatura</option>
        </select>

        <button
          @click="showAdvancedFilters = !showAdvancedFilters"
          class="text-xs text-purple-600 hover:text-purple-700 flex items-center"
        >
          {{ showAdvancedFilters ? 'Esconder' : 'Mostrar' }} Filtros Avançados
          <svg
            class="w-3 h-3 ml-1"
            :class="{ 'transform rotate-180': showAdvancedFilters }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 9l-7 7-7-7"
            />
          </svg>
        </button>
      </div>

      <div class="flex items-center">
        <button
          @click="$emit('refresh')"
          class="p-1 text-gray-400 hover:text-purple-600 focus:outline-none"
          title="Refresh data"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
            />
          </svg>
        </button>
        <button
          @click="$emit('clear-filters')"
          class="p-1 ml-1 text-xs text-purple-600 hover:text-purple-800 focus:outline-none"
        >
          Limpar Filtros
        </button>
      </div>
    </div>

    <!-- Filtros avançados -->
    <div v-if="showAdvancedFilters" class="bg-gray-50 p-3 rounded-md space-y-3">
      <div class="flex flex-wrap gap-3">
        <div class="space-y-1">
          <label class="text-xs text-gray-600">Preço</label>
          <div class="flex gap-2 items-center">
            <input
              v-model="minAmount"
              type="number"
              min="0"
              step="0.01"
              placeholder="Min"
              class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5 w-24"
              @change="onFilterChange"
            />
            <span class="text-xs text-gray-500">até</span>
            <input
              v-model="maxAmount"
              type="number"
              min="0"
              step="0.01"
              placeholder="Max"
              class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5 w-24"
              @change="onFilterChange"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'

const props = defineProps({
  defaultStartDate: {
    type: String,
    default: '',
  },
  defaultEndDate: {
    type: String,
    default: '',
  },
  defaultSort: {
    type: Object,
    default: () => ({ sortBy: 'transactions.created_at', sortDirection: 'desc' }),
  },
})

const emit = defineEmits(['filter-change', 'refresh', 'clear-filters'])

const startDate = ref(props.defaultStartDate)
const endDate = ref(props.defaultEndDate)
const selectedStatus = ref<string | null>(null)
const selectedType = ref<string | null>(null)
const customer = ref('')
const showAdvancedFilters = ref(false)

// Filtros avançados
const minAmount = ref<string>('')
const maxAmount = ref<string>('')
const sortBy = ref(props.defaultSort.sortBy)
const sortDirection = ref(props.defaultSort.sortDirection)

// Debounce para o campo de busca
let searchTimeout: number | null = null

function debounceSearch() {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }

  searchTimeout = window.setTimeout(() => {
    onFilterChange()
  }, 500)
}

function onFilterChange() {
  const filters = {
    customer: customer.value?.trim(),
    status: selectedStatus.value,
    type: selectedType.value,
    startDate: startDate.value,
    endDate: endDate.value,
    minAmount: minAmount.value ? parseFloat(minAmount.value) : undefined,
    maxAmount: maxAmount.value ? parseFloat(maxAmount.value) : undefined,
    sortBy: sortBy.value,
    sortDirection: sortDirection.value,
  }

  emit('filter-change', filters)
}

// Observar mudanças nos filtros básicos para aplicação automática
watch([selectedStatus, selectedType], () => {
  onFilterChange()
})

// Limpar todos os filtros
function clearAllFilters() {
  selectedStatus.value = null
  selectedType.value = null
  customer.value = ''
  startDate.value = ''
  endDate.value = ''
  minAmount.value = undefined
  maxAmount.value = undefined
  sortBy.value = 'transactions.created_at'
  sortDirection.value = 'desc'

  onFilterChange()
}

// Expor a função de limpar filtros
defineExpose({
  clearFilters: clearAllFilters,
})
</script>
