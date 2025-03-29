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
          v-model="userName"
          placeholder="Nome do Usuário"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @input="debounceSearch"
        />

        <input
          v-model="productName"
          placeholder="Nome do Produto"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @input="debounceSearch"
        />

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
        <div class="flex gap-2 items-center">
          <label class="text-xs text-gray-600">Categoria</label>
          <select
            v-model="categoryId"
            class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
            @change="onFilterChange"
          >
            <option :value="null">Todas</option>
            <option value="1">Eletrônicos</option>
            <option value="2">Roupas</option>
            <option value="3">Alimentos</option>
            <option value="4">Livros</option>
            <option value="5">Casa</option>
            <option value="6">Esportes</option>
            <option value="7">Beleza</option>
            <option value="8">Saúde</option>
            <option value="9">Brinquedos</option>
            <option value="10">Ferramentas</option>
          </select>
        </div>

        <div class="flex gap-2 items-center">
          <label class="text-xs text-gray-600">Status</label>
          <select
            v-model="selectedStatus"
            class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
            @change="onFilterChange"
          >
            <option :value="null">Todos</option>
            <option value="success">Sucesso</option>
            <option value="warning">Aguardando Pagamento</option>
            <option value="danger">Cancelado</option>
          </select>
        </div>

        <div class="flex gap-2 items-center">
          <label class="text-xs text-gray-600">Região</label>
          <select
            v-model="region"
            class="text-xs bg-white border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
            @change="onFilterChange"
          >
            <option :value="null">Todas</option>
            <option value="Brazil">Brasil</option>
            <option value="Europe">Europa</option>
            <option value="EUA">EUA</option>
            <option value="Outros">Outros</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { SalesParams } from '../../api/dashboard/types'

const props = defineProps({
  defaultStatus: {
    type: String,
    default: '',
  },
  defaultStartDate: {
    type: String,
    default: '',
  },
  defaultEndDate: {
    type: String,
    default: '',
  },
  defaultOrderBy: {
    type: String,
    default: 'created_at',
  },
  defaultOrderDirection: {
    type: String,
    default: 'desc',
  },
})

const emit = defineEmits(['filter-change', 'refresh', 'clear-filters'])

// Estado dos filtros
const startDate = ref(props.defaultStartDate)
const endDate = ref(props.defaultEndDate)
const selectedStatus = ref(props.defaultStatus)
const userName = ref<string | null>(null)
const productName = ref<string | null>(null)
const showAdvancedFilters = ref(false)

// Filtros avançados
const categoryId = ref<number | null>(null)
const region = ref<string | null>(null)

// Debounce para os campos de busca
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
  const filters: Partial<SalesParams> = {
    startDate: startDate.value,
    endDate: endDate.value,
    userName: !userName.value || userName.value === '' ? null : userName.value.trim(),
    productName: !productName.value || productName.value === '' ? null : productName.value.trim(),
    categoryId: categoryId.value,
    region: region.value
  }

  emit('filter-change', filters)
}

// Limpar todos os filtros
function clearAllFilters() {
  startDate.value = new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0]
  endDate.value = new Date().toISOString().split('T')[0]
  userName.value = null
  productName.value = null
  categoryId.value = null
  region.value = null

  onFilterChange()
}

// Expor a função de limpar filtros
defineExpose({
  clearFilters: clearAllFilters,
})
</script>
