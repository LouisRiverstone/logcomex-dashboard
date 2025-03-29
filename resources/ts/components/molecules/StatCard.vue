<template>
  <div class="bg-white rounded-lg shadow-sm p-4 h-full">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500 mb-1">{{ title }}</p>
        <h3 class="text-2xl font-semibold text-gray-800">{{ value }}</h3>
        
        <div v-if="trend && trend.direction && trend.value !== undefined" class="mt-2 flex items-center">
          <span 
            :class="[
              'inline-flex items-center text-xs font-medium mr-1',
              trend.direction === 'up' ? 'text-green-600' : 'text-red-600'
            ]"
          >
            <svg 
              v-if="trend.direction === 'up'" 
              class="w-3 h-3 mr-1" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
            <svg 
              v-else 
              class="w-3 h-3 mr-1" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            {{ Math.abs(trend.value) }}%
          </span>
          <span class="text-xs text-gray-500">{{ trend.label || '' }}</span>
        </div>
        
        <p v-if="period" class="mt-1 text-sm text-gray-500">{{ period }}</p>
      </div>
      
      <div v-if="icon" class="p-2 rounded-lg" :class="iconBackgroundClass">
        <component :is="icon" class="w-5 h-5" :class="iconColorClass" />
      </div>
    </div>
    
    <div v-if="sparklineData && sparklineData.length > 0" class="mt-3 h-10">
      <svg class="w-full h-full" viewBox="0 0 100 20" preserveAspectRatio="none">
        <path
          :d="getSparklinePath(sparklineData)"
          fill="none"
          :stroke="sparklineColor"
          stroke-width="1.5"
        />
      </svg>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  period: {
    type: String,
    default: ''
  },
  trend: {
    type: Object as () => {
      value: number;
      direction: 'up' | 'down';
      label?: string;
    } | null,
    default: null
  },
  icon: {
    type: [String, Object],
    default: null
  },
  color: {
    type: String,
    default: 'purple'
  },
  sparklineData: {
    type: Array as () => number[],
    default: () => []
  }
});

const iconColorClass = computed(() => {
  return {
    'purple': 'text-purple-600',
    'blue': 'text-blue-600',
    'green': 'text-green-600',
    'red': 'text-red-600',
    'yellow': 'text-yellow-600',
    'indigo': 'text-indigo-600',
    'pink': 'text-pink-600'
  }[props.color] || 'text-purple-600';
});

const iconBackgroundClass = computed(() => {
  return {
    'purple': 'bg-purple-100',
    'blue': 'bg-blue-100',
    'green': 'bg-green-100',
    'red': 'bg-red-100',
    'yellow': 'bg-yellow-100',
    'indigo': 'bg-indigo-100',
    'pink': 'bg-pink-100'
  }[props.color] || 'bg-purple-100';
});

const sparklineColor = computed(() => {
  return {
    'purple': '#8b5cf6',
    'blue': '#3b82f6',
    'green': '#10b981',
    'red': '#ef4444',
    'yellow': '#f59e0b',
    'indigo': '#6366f1',
    'pink': '#ec4899'
  }[props.color] || '#8b5cf6';
});

function getSparklinePath(data: number[]): string {
  if (!data || data.length === 0) return '';
  
  const max = Math.max(...data);
  const min = Math.min(...data);
  const range = max - min || 1;
  
  const width = 100;
  const height = 20;
  const points = data.map((value, index) => {
    const x = (index / (data.length - 1)) * width;
    const y = height - ((value - min) / range) * height;
    return `${x},${y}`;
  });
  
  return `M${points.join(' L')}`;
}
</script> 