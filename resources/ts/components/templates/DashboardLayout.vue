<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-purple-600">LogComex Dashboard</h1>
            </div>
          </div>
          <div class="flex items-center">
            <button class="rounded-full bg-gray-100 p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
              <span class="sr-only">View notifications</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </button>
            <div class="ml-3 relative">
              <div class="flex items-center">
                <button class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none">
                  <span class="sr-only">Open user menu</span>
                  <div class="h-8 w-8 rounded-full bg-purple-600 flex items-center justify-center">
                    <span class="text-white text-sm font-medium">AU</span>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1">
      <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ pageTitle }}
              </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
              <slot name="actions"></slot>
            </div>
          </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <slot></slot>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  pageTitle: {
    type: String,
    default: 'Dashboard'
  }
});
</script> 