<template>
  <Teleport to="body">
    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="modelValue" class="fixed inset-0 bg-transparent bg-opacity-50 backdrop-blur-sm transition-opacity z-40 backdrop-filter"></div>
    </Transition>

    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      enter-to-class="opacity-100 translate-y-0 sm:scale-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100 translate-y-0 sm:scale-100"
      leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
      <div v-if="modelValue" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="relative transform overflow-hidden rounded-lg bg-white shadow-2xl bg-opacity-90 backdrop-filter backdrop-blur-md text-left  transition-all sm:my-8 sm:w-full" :class="[sizeClass]">
            <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
              <div v-if="title" class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ title }}</h3>
                <button
                  type="button"
                  class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none"
                  @click="closeModal"
                >
                  <span class="sr-only">Close</span>
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                  <slot></slot>
                </div>
              </div>
            </div>
            <div v-if="$slots.footer" class="bg-gray-50 bg-opacity-90 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <slot name="footer"></slot>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg', 'xl', '2xl', 'full'].includes(value)
  }
});

const emit = defineEmits(['update:modelValue']);

const sizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'sm:max-w-sm';
    case 'md': return 'sm:max-w-md';
    case 'lg': return 'sm:max-w-lg';
    case 'xl': return 'sm:max-w-xl';
    case '2xl': return 'sm:max-w-2xl';
    case 'full': return 'sm:max-w-full';
    default: return 'sm:max-w-md';
  }
});

function closeModal() {
  emit('update:modelValue', false);
}
</script>
