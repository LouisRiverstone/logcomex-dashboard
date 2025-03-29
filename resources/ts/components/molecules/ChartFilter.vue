<template>
  <div class="flex flex-col gap-2 mb-4">
    <!-- Time Period and Basic Filters Row -->
    <div class="flex flex-wrap gap-2 items-center justify-between">
      <div class="flex flex-wrap gap-2 items-center">
        <!-- Period Selector -->
        <select
          v-if="showPeriod"
          v-model="selectedPeriod"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @change="onFilterChange"
        >
          <option v-for="period in periods" :key="period.value" :value="period.value">
            {{ period.label }}
          </option>
          <!-- TODO: Não vai dar tempo -->
          <!--<option v-if="showDateRange" value="custom">Custom range</option>-->
        </select>
        
        <!-- Categories Selector -->
        <select
          v-if="showCategories && categories.length"
          v-model="selectedCategory"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @change="onFilterChange"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        
        <!-- Comparison Checkbox -->
        <div v-if="showComparison" class="flex items-center">
          <input
            type="checkbox"
            id="comparison"
            v-model="enableComparison"
            class="h-3.5 w-3.5 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
            @change="onFilterChange"
          />
          <label for="comparison" class="ml-1 text-xs text-gray-700">Compare with previous</label>
        </div>
      </div>
      
      <!-- Action Buttons -->
      <div class="flex items-center">
        <button
          @click="$emit('refresh')"
          class="p-1 text-gray-400 hover:text-purple-600 focus:outline-none"
          title="Refresh data"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        </button>
        <button
          v-if="showOptions"
          @click="$emit('toggle-options')"
          class="p-1 ml-1 text-gray-400 hover:text-purple-600 focus:outline-none"
          title="Options"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Date Range Row -->
    <div v-if="showDateRange && selectedPeriod === 'custom'" class="flex flex-wrap gap-2 items-center">
      <div class="flex items-center gap-2">
        <input
          type="date"
          v-model="startDate"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @change="onFilterChange"
        />
        <span class="text-xs text-gray-500">to</span>
        <input
          type="date"
          v-model="endDate"
          class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
          @change="onFilterChange"
        />
      </div>
    </div>
    
    <!-- Revenue Types / Products / Competitors Row -->
    <div v-if="showRevenueTypes || showProducts || showCompetitors" class="flex flex-wrap gap-2 items-center">
      <!-- Revenue Types -->
      <select
        v-if="showRevenueTypes && revenueTypes.length"
        v-model="selectedRevenueTypes"
        multiple
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option v-for="type in revenueTypes" :key="type.id" :value="type.id">
          {{ type.name }}
        </option>
      </select>
      
      <!-- Products Selector -->
      <select
        v-if="showProducts"
        v-model="selectedProductId"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option value="">All Products</option>
        <option v-for="product in products" :key="product.id" :value="product.id">
          {{ product.name }}
        </option>
      </select>
      
      <!-- Competitors Selector -->
      <select
        v-if="showCompetitors"
        v-model="selectedCompetitorId"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option value="">All Competitors</option>
        <option v-for="competitor in competitors" :key="competitor.id" :value="competitor.id">
          {{ competitor.name }}
        </option>
      </select>
    </div>
    
    <!-- Search / Sort / Status / Type Row -->
    <div v-if="showSearch || showSort || showStatus || showType" class="flex flex-wrap gap-2 items-center">
      <!-- Search Input -->
      <input
        v-if="showSearch"
        v-model="searchTerm"
        type="text"
        placeholder="Search..."
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @input="debounceSearch"
      />
      
      <!-- Sort By -->
      <select
        v-if="showSort && sortOptions.length"
        v-model="selectedSortBy"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option v-for="option in sortOptions" :key="option.id" :value="option.id">
          Sort by: {{ option.name }}
        </option>
      </select>
      
      <!-- Sort Direction -->
      <select
        v-if="showSort && sortDirections.length"
        v-model="selectedSortDirection"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option v-for="direction in sortDirections" :key="direction.id" :value="direction.id">
          {{ direction.name }}
        </option>
      </select>
      
      <!-- Status -->
      <select
        v-if="showStatus"
        v-model="selectedStatus"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
        <option value="failed">Failed</option>
      </select>
      
      <!-- Type -->
      <select
        v-if="showType"
        v-model="selectedType"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      >
        <option value="">All Types</option>
        <option value="purchase">Purchase</option>
        <option value="refund">Refund</option>
        <option value="subscription">Subscription</option>
      </select>
    </div>
    
    <!-- Amount Range Row -->
    <div v-if="showAmountRange" class="flex flex-wrap gap-2 items-center">
      <input
        v-model="minAmount"
        type="number"
        placeholder="Min amount"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      />
      <span class="text-xs text-gray-500">to</span>
      <input
        v-model="maxAmount"
        type="number"
        placeholder="Max amount"
        class="text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded-md focus:ring-purple-500 focus:border-purple-500 p-1.5"
        @change="onFilterChange"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue';

// Props definition
const props = defineProps({
  // Basic options
  showPeriod: {
    type: Boolean,
    default: true
  },
  showCategories: {
    type: Boolean,
    default: false
  },
  showComparison: {
    type: Boolean,
    default: false
  },
  showOptions: {
    type: Boolean,
    default: true
  },
  // New options
  showDateRange: {
    type: Boolean,
    default: false
  },
  showRevenueTypes: {
    type: Boolean,
    default: false
  },
  showProducts: {
    type: Boolean,
    default: false
  },
  showCompetitors: {
    type: Boolean,
    default: false
  },
  showSearch: {
    type: Boolean,
    default: false
  },
  showSort: {
    type: Boolean,
    default: false
  },
  showStatus: {
    type: Boolean,
    default: false
  },
  showType: {
    type: Boolean,
    default: false
  },
  showAmountRange: {
    type: Boolean,
    default: false
  },
  // Data arrays
  categories: {
    type: Array as () => Array<{id: string | number, name: string}>,
    default: () => []
  },
  revenueTypes: {
    type: Array as () => Array<{id: string | number, name: string}>,
    default: () => []
  },
  products: {
    type: Array as () => Array<{id: string | number, name: string}>,
    default: () => []
  },
  competitors: {
    type: Array as () => Array<{id: string | number, name: string}>,
    default: () => []
  },
  sortOptions: {
    type: Array as () => Array<{id: string, name: string}>,
    default: () => []
  },
  sortDirections: {
    type: Array as () => Array<{id: string, name: string}>,
    default: () => [
      { id: 'asc', name: 'Ascending' },
      { id: 'desc', name: 'Descending' }
    ]
  },
  defaultPeriod: {
    type: String,
    default: '30d'
  }
});

const emit = defineEmits(['filter-change', 'refresh', 'toggle-options']);

// Predefined options
const periods = [
  { value: '7d', label: '7 dias' },
  { value: '30d', label: '30 dias' },
  { value: '90d', label: '90 dias' },
  { value: '1y', label: '1 Ano' },
  { value: '2y', label: '2 Anos' },
  { value: 'all', label: 'Todos' }
];

// Basic filter state
const selectedPeriod = ref(props.defaultPeriod);
const selectedCategory = ref('');
const enableComparison = ref(false);

// Date range filter state
const startDate = ref('');
const endDate = ref('');

// Revenue types, products, competitors filter state
const selectedRevenueTypes = ref([]);
const selectedProductId = ref('');
const selectedCompetitorId = ref('');

// Search, sort, status, type filter state
const searchTerm = ref('');
const selectedSortBy = ref(props.sortOptions.length ? props.sortOptions[0].id : '');
const selectedSortDirection = ref('desc');
const selectedStatus = ref('');
const selectedType = ref('');

// Amount range filter state
const minAmount = ref('');
const maxAmount = ref('');

// Watch for changes to reactive values
watch([
  selectedPeriod, 
  selectedCategory, 
  enableComparison, 
  startDate, 
  endDate,
  selectedRevenueTypes,
  selectedProductId,
  selectedCompetitorId,
  selectedSortBy,
  selectedSortDirection,
  selectedStatus,
  selectedType,
  minAmount,
  maxAmount
], () => {
  onFilterChange();
});

// Debounce search input to avoid too many API calls
let searchTimeout: number | null = null;
function debounceSearch() {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  
  searchTimeout = setTimeout(() => {
    onFilterChange();
  }, 300) as unknown as number;
}

// Send all filter values to parent
function onFilterChange() {
  emit('filter-change', {
    // Basic filters
    period: selectedPeriod.value,
    category: selectedCategory.value,
    comparison: enableComparison.value,
    
    // Date range
    startDate: selectedPeriod.value === 'custom' ? startDate.value : '',
    endDate: selectedPeriod.value === 'custom' ? endDate.value : '',
    
    // Revenue types, products, competitors
    types: selectedRevenueTypes.value,
    productId: selectedProductId.value || undefined,
    competitorId: selectedCompetitorId.value || undefined,
    
    // Search, sort, status, type
    searchTerm: searchTerm.value,
    sortBy: selectedSortBy.value,
    sortDirection: selectedSortDirection.value,
    status: selectedStatus.value,
    type: selectedType.value,
    
    // Amount range
    minAmount: minAmount.value,
    maxAmount: maxAmount.value
  });
}
</script> 