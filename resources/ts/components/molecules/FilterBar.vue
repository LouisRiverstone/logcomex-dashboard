<template>
  <div class="bg-white rounded-lg shadow-sm p-3 mb-4">
    <div class="flex flex-wrap gap-2 sm:gap-4 items-center">
      <div class="flex-shrink-0">
        <select
          v-model="selectedPeriod"
          class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500 block w-full p-2"
          @change="onPeriodChange"
        >
          <option v-for="period in periods" :key="period.value" :value="period.value">
            {{ period.label }}
          </option>
        </select>
      </div>
      
      <div v-if="showDateRange" class="flex items-center space-x-2 flex-grow md:flex-grow-0">
        <input
          type="date"
          v-model="startDate"
          class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500 block w-full p-2"
        />
        <span class="text-gray-500">-</span>
        <input
          type="date"
          v-model="endDate"
          class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500 block w-full p-2"
        />
      </div>
      
      <div class="ml-auto">
        <slot name="actions"></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue';

const props = defineProps({
  defaultPeriod: {
    type: String,
    default: '30d'
  }
});

const emit = defineEmits(['filter-change']);

const periods = [
  { value: '7d', label: 'Last 7 days' },
  { value: '30d', label: 'Last 30 days' },
  { value: '90d', label: 'Last 90 days' },
  { value: '1y', label: 'Last year' },
  { value: 'custom', label: 'Custom range' }
];

const selectedPeriod = ref(props.defaultPeriod);
const showDateRange = ref(selectedPeriod.value === 'custom');
const startDate = ref('');
const endDate = ref('');

watch(selectedPeriod, (newVal) => {
  showDateRange.value = newVal === 'custom';
  emitFilterChange();
});

watch([startDate, endDate], () => {
  if (selectedPeriod.value === 'custom' && startDate.value && endDate.value) {
    emitFilterChange();
  }
});

function onPeriodChange() {
  showDateRange.value = selectedPeriod.value === 'custom';
  emitFilterChange();
}

function emitFilterChange() {
  emit('filter-change', {
    period: selectedPeriod.value,
    startDate: startDate.value,
    endDate: endDate.value
  });
}
</script> 