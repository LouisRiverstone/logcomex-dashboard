<template>
  <div 
    class="bg-white rounded-lg shadow-sm overflow-hidden" 
    :class="[padding ? 'p-0' : '', className]"
  >
    <div v-if="$slots.header" class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
      <slot name="header"></slot>
    </div>
    <div :class="padding ? 'p-4' : ''">
      <slot></slot>
    </div>
    <div v-if="$slots.footer" class="px-4 py-3 border-t border-gray-100 bg-gray-50">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  padding: {
    type: Boolean,
    default: true
  },
  className: {
    type: String,
    default: ''
  }
});
</script> 