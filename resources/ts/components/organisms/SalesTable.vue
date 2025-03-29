<template>

<Panel :title="title" class="h-full">
    <template #actions>
      <slot name="panel-actions"></slot>
    </template>

    <div class="h-full flex flex-col">
      <SalesFilter 
        :default-status="defaultStatus"
        :default-start-date="defaultStartDate"
        :default-end-date="defaultEndDate"
        @filter-change="handleFilterChange"
        @refresh="refresh"
        @clear-filters="clearFilters"
        ref="filterRef"
      />

      <slot></slot>

      <div class="border-t border-gray-200 mt-4"></div>
    
      <div class="flex-1 min-h-0">
        <div v-if="loading" class="h-full flex items-center justify-center">
          <Spinner size="md" />
        </div>
        <div v-else-if="error" class="h-full flex items-center justify-center">
          <p class="text-sm text-red-500">{{ error }}</p>
        </div>
        <div v-else class="h-full flex flex-col">
          <div class="overflow-x-auto flex-1">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="toggleSort('user_name')">
                    <div class="flex items-center">
                      Cliente
                      <span v-if="sortBy === 'user_name'" class="ml-1">
                        <svg class="w-3 h-3" :class="{ 'transform rotate-180': sortDirection === 'asc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </span>
                    </div>
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="toggleSort('product_name')">
                    <div class="flex items-center">
                      Produto
                      <span v-if="sortBy === 'product_name'" class="ml-1">
                        <svg class="w-3 h-3" :class="{ 'transform rotate-180': sortDirection === 'asc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </span>
                    </div>
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="toggleSort('amount')">
                    <div class="flex items-center">
                      Valor
                      <span v-if="sortBy === 'amount'" class="ml-1">
                        <svg class="w-3 h-3" :class="{ 'transform rotate-180': sortDirection === 'asc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </span>
                    </div>
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="toggleSort('status')">
                    <div class="flex items-center">
                      Status
                      <span v-if="sortBy === 'status'" class="ml-1">
                        <svg class="w-3 h-3" :class="{ 'transform rotate-180': sortDirection === 'asc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </span>
                    </div>
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="toggleSort('created_at')">
                    <div class="flex items-center">
                      Data
                      <span v-if="sortBy === 'created_at'" class="ml-1">
                        <svg class="w-3 h-3" :class="{ 'transform rotate-180': sortDirection === 'asc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </span>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="sale in sales" :key="sale.id" @click="openSaleDetails(sale.id)" class="hover:bg-gray-50 cursor-pointer">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-800">
                        {{ sale.user_name.charAt(0) }}
                      </div>
                      <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ sale.user_name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ sale.product_name }}</div>
                    <div class="text-xs text-gray-500">{{ sale.category_name }}</div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ formatCurrency(sale.amount) }}</div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <Badge :variant="sale.status">
                      {{ sale.status }}
                    </Badge>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(sale.created_at) }}
                  </td>
                </tr>
                <tr v-if="sales.length === 0">
                  <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                    Nenhuma venda encontrada
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Paginação -->
          <div class="py-3 border-t border-gray-200 bg-white flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button
                @click="goToPreviousPage"
                :disabled="currentPage === 1"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white',
                  currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'
                ]"
              >
                Anterior
              </button>
              <button
                @click="goToNextPage"
                :disabled="currentPage === totalPages"
                :class="[
                  'ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white',
                  currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'
                ]"
              >
                Próximo
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Exibindo
                  <span class="font-medium">{{ startItem }}</span>
                  até
                  <span class="font-medium">{{ endItem }}</span>
                  de
                  <span class="font-medium">{{ totalItems }}</span>
                  resultados
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button
                    @click="goToPreviousPage"
                    :disabled="currentPage === 1"
                    :class="[
                      'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500',
                      currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'
                    ]"
                  >
                    <span class="sr-only">Anterior</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div v-for="page in displayedPages" :key="page">
                    <button
                      v-if="page !== '...'"
                      @click="goToPage(page)"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                        currentPage === page 
                          ? 'z-10 bg-purple-50 border-purple-500 text-purple-600' 
                          : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                      ]"
                    >
                      {{ page }}
                    </button>
                    <span
                      v-else
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                    >
                      ...
                    </span>
                  </div>
                  <button
                    @click="goToNextPage"
                    :disabled="currentPage === totalPages"
                    :class="[
                      'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500',
                      currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'
                    ]"
                  >
                    <span class="sr-only">Próximo</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Panel>

  <!-- Modal de Detalhes da Venda -->
  <Modal v-model="showDetailsModal" :title="'Detalhes da Venda'" size="lg" class="bg-transparent">
    <SaleDetailView 
      :sale="selectedSale"
      :loading="loadingDetails"
      :error="detailsError"
    />
    <template #footer>
      <Button 
        variant="outline" 
        @click="showDetailsModal = false"
      >
        Fechar
      </Button>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import { ref, computed, inject } from 'vue';
import Panel from '@/components/atoms/Panel.vue';
import Badge from '@/components/atoms/Badge.vue';
import Spinner from '@/components/atoms/Spinner.vue';
import SalesFilter from '@/components/molecules/SalesFilter.vue';
import Modal from '@/components/atoms/Modal.vue';
import Button from '@/components/atoms/Button.vue';
import SaleDetailView from '@/components/molecules/SaleDetailView.vue';
import { SalesData, SaleDetails } from '@/api/dashboard/types';
import { useApi } from '@/composables/api';

const api = useApi();

const props = defineProps({
  title: {
    type: String,
    default: 'Sales'
  },
  sales: {
    type: Array as () => SalesData[],
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  },
  defaultStatus: {
    type: String| null,
    default: null
  },
  defaultStartDate: {
    type: String,
    default: ''
  },
  defaultEndDate: {
    type: String,
    default: ''
  },
  totalItems: {
    type: Number,
    default: 0
  },
  currentPage: {
    type: Number,
    default: 1
  },
  perPage: {
    type: Number,
    default: 10
  }
});

const emit = defineEmits(['filter-change', 'refresh', 'page-change']);

// Ref para acessar os métodos do componente de filtro
const filterRef = ref(null);

// Estado para sorting
const sortBy = ref('created_at');
const sortDirection = ref('desc');

// Estado para modal de detalhes
const showDetailsModal = ref(false);
const selectedSale = ref<SaleDetails | SalesData | null>(null);
const loadingDetails = ref(false);
const detailsError = ref('');

// Métodos do componente
function handleFilterChange(filters: any) {
  emit('filter-change', {
    ...filters,
    page: 1, // Voltar para a primeira página quando mudar os filtros
    per_page: props.perPage
  });
}

function refresh() {
  emit('refresh');
}

function clearFilters() {
  if (filterRef.value) {
    (filterRef.value as any).clearFilters();
  }
}

function toggleSort(field: string) {
  if (sortBy.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = field;
    sortDirection.value = 'desc';
  }
  
  emit('filter-change', {
    orderBy: sortBy.value,
    orderDirection: sortDirection.value,
    page: props.currentPage,
    perPage: props.perPage
  });
}


function formatDate(dateString: string): string {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  }).format(date);
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount);
}

// Paginação
const totalPages = computed(() => {
  return Math.max(1, Math.ceil(props.totalItems / props.perPage));
});

const startItem = computed(() => {
  return (props.currentPage - 1) * props.perPage + 1;
});

const endItem = computed(() => {
  return Math.min(props.currentPage * props.perPage, props.totalItems);
});

const displayedPages = computed(() => {
  const range = [];
  const maxPagesToShow = 5;
  
  if (totalPages.value <= maxPagesToShow) {
    // Mostrar todas as páginas se forem poucas
    for (let i = 1; i <= totalPages.value; i++) {
      range.push(i);
    }
  } else {
    // Lógica para mostrar páginas com ellipsis
    const leftSide = Math.floor(maxPagesToShow / 2);
    const rightSide = maxPagesToShow - leftSide - 1;
    
    // Páginas iniciais
    if (props.currentPage <= leftSide + 1) {
      for (let i = 1; i <= leftSide + 1; i++) {
        range.push(i);
      }
      range.push('...');
      range.push(totalPages.value - 1);
      range.push(totalPages.value);
    }
    // Páginas finais
    else if (props.currentPage >= totalPages.value - rightSide) {
      range.push(1);
      range.push(2);
      range.push('...');
      for (let i = totalPages.value - leftSide; i <= totalPages.value; i++) {
        range.push(i);
      }
    }
    // Páginas do meio
    else {
      range.push(1);
      range.push('...');
      for (let i = props.currentPage - 1; i <= props.currentPage + 1; i++) {
        range.push(i);
      }
      range.push('...');
      range.push(totalPages.value);
    }
  }
  
  return range;
});

function goToPage(page: number) {
  emit('filter-change', {
    page,
    per_page: props.perPage,
    order_by: sortBy.value,
    order_direction: sortDirection.value
  });
}

function goToPreviousPage() {
  if (props.currentPage > 1) {
    goToPage(props.currentPage - 1);
  }
}

function goToNextPage() {
  if (props.currentPage < totalPages.value) {
    goToPage(props.currentPage + 1);
  }
}

// Função para abrir o modal de detalhes
async function openSaleDetails(id: number) {
  showDetailsModal.value = true;
  loadingDetails.value = true;
  detailsError.value = '';
  
  try {
    const response = await api.dashboard.getSaleDetails(id);
    selectedSale.value = response.data;
  } catch (error) {
    console.error('Error fetching sale details:', error);
    detailsError.value = 'Erro ao carregar os detalhes da venda.';
  } finally {
    loadingDetails.value = false;
  }
}
</script>
