<template>
  <button
    :class="[
      'inline-flex items-center justify-center focus:outline-none transition-colors',
      sizeClasses,
      variantClasses,
      {'opacity-50 cursor-not-allowed': disabled},
      className
    ]"
    :disabled="disabled"
    :type="type"
    @click="$emit('click', $event)"
  >
    <slot name="icon-left"></slot>
    <slot></slot>
    <slot name="icon-right"></slot>
  </button>
</template>

<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value: string) => ['primary', 'secondary', 'outline', 'ghost', 'danger'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg'].includes(value)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'button'
  },
  className: {
    type: String,
    default: ''
  }
});

defineEmits(['click']);

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm': return 'text-xs px-2.5 py-1.5 rounded-md';
    case 'lg': return 'text-base px-4 py-3 rounded-lg';
    default: return 'text-sm px-3 py-2 rounded-md';
  }
});

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'primary': return 'bg-purple-600 hover:bg-purple-700 text-white';
    case 'secondary': return 'bg-purple-100 hover:bg-purple-200 text-purple-800';
    case 'outline': return 'border border-purple-600 text-purple-600 hover:bg-purple-50';
    case 'ghost': return 'text-purple-600 hover:bg-purple-50';
    case 'danger': return 'bg-red-600 hover:bg-red-700 text-white';
    default: return 'bg-purple-600 hover:bg-purple-700 text-white';
  }
});
</script> 