<template>
  <span 
    class="inline-flex items-center justify-center font-medium text-xs rounded-full px-2.5 py-0.5"
    :class="[variantClass, className]"
  >
    <slot></slot>
  </span>
</template>

<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value: string) => ['primary', 'secondary', 'success', 'warning', 'danger', 'info'].includes(value)
  },
  className: {
    type: String,
    default: ''
  }
});

const variantClass = computed(() => {
  switch (props.variant) {
    case 'primary': return 'bg-purple-100 text-purple-800';
    case 'secondary': return 'bg-gray-100 text-gray-800';
    case 'success': return 'bg-green-100 text-green-800';
    case 'warning': return 'bg-yellow-100 text-yellow-800';
    case 'danger': return 'bg-red-100 text-red-800';
    case 'info': return 'bg-blue-100 text-blue-800';
    default: return 'bg-purple-100 text-purple-800';
  }
});
</script> 