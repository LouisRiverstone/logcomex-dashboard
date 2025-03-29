<template>
  <Panel :title="title" class="h-full">
    <template #actions>
      <slot name="panel-actions"></slot>
    </template>
    
    <div class="h-full flex flex-col">
      <ChartFilter
        :show-period="filterOptions.showPeriod"
        :show-categories="filterOptions.showCategories" 
        :show-comparison="filterOptions.showComparison"
        :show-options="filterOptions.showOptions"
        :show-date-range="filterOptions.showDateRange"
        :show-revenue-types="filterOptions.showRevenueTypes"
        :show-products="filterOptions.showProducts"
        :show-competitors="filterOptions.showCompetitors"
        :show-search="filterOptions.showSearch"
        :show-sort="filterOptions.showSort"
        :show-status="filterOptions.showStatus"
        :show-type="filterOptions.showType"
        :show-amount-range="filterOptions.showAmountRange"
        :categories="filterOptions.categories || []"
        :revenue-types="filterOptions.revenueTypes || []"
        :sort-options="filterOptions.sortOptions || []"
        :sort-directions="filterOptions.sortDirections || []"
        :default-period="filterOptions.defaultPeriod"
        @filter-change="handleFilterChange"
        @refresh="refresh"
        @toggle-options="$emit('toggle-options')"
      />
      
      <div class="flex-1 min-h-0">
        <div v-if="loading" class="h-full flex items-center justify-center">
          <Spinner size="md" />
        </div>
        <div v-else-if="error" class="h-full flex items-center justify-center">
          <p class="text-sm text-red-500">{{ error }}</p>
        </div>
        <div v-else class="h-full">
          <slot></slot>
        </div>
      </div>
    </div>
  </Panel>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue';
import Panel from '../atoms/Panel.vue';
import Spinner from '../atoms/Spinner.vue';
import ChartFilter from '../molecules/ChartFilter.vue';

const props = defineProps({
  title: {
    type: String,
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
  filterOptions: {
    type: Object,
    default: () => ({
      showPeriod: true,
      showCategories: false,
      showComparison: false,
      showOptions: true,
      showDateRange: false,
      showRevenueTypes: false,
      showProducts: false,
      showCompetitors: false,
      showSearch: false,
      showSort: false,
      showStatus: false,
      showType: false,
      showAmountRange: false,
      categories: [],
      revenueTypes: [],
      sortOptions: [],
      sortDirections: [],
      defaultPeriod: '30d'
    })
  }
});

const emit = defineEmits(['filter-change', 'refresh', 'toggle-options']);

function handleFilterChange(filters: any) {
  emit('filter-change', filters);
}

function refresh() {
  emit('refresh');
}
</script> 