<template>
  <div class="flex justify-center items-center">
    <svg 
      :class="[sizeClass, 'animate-spin text-purple-600']" 
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle 
        class="opacity-25" 
        cx="12" 
        cy="12" 
        r="10" 
        stroke="currentColor" 
        stroke-width="4"
      ></circle>
      <path 
        class="opacity-75" 
        fill="currentColor" 
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps({
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg'].includes(value)
  }
});

const sizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'h-4 w-4';
    case 'md': return 'h-8 w-8';
    case 'lg': return 'h-12 w-12';
    default: return 'h-8 w-8';
  }
});
</script> 