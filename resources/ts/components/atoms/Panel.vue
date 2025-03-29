<template>
  <div class="bg-white rounded-lg shadow-sm h-full" :class="className">
    <div v-if="title || $slots.actions" class="flex justify-between items-center p-4 border-b border-gray-100">
      <h3 v-if="title" class="text-base font-medium text-gray-800">{{ title }}</h3>
      <div v-if="$slots.actions">
        <slot name="actions"></slot>
      </div>
    </div>
    <div :class="contentClass">
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  title: {
    type: String,
    default: ''
  },
  contentClass: {
    type: String,
    default: 'p-4'
  },
  className: {
    type: String,
    default: ''
  }
});
</script> 