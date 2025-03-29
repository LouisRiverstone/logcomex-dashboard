<template>
  <div>
    <div v-if="loading" class="py-10 flex items-center justify-center">
      <Spinner size="md" />
    </div>
    <div v-else-if="error" class="py-10 flex items-center justify-center">
      <p class="text-sm text-red-500">{{ error }}</p>
    </div>
    <div v-else-if="sale" class="space-y-4">
      <!-- Customer Info -->
      <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Cliente</h4>
        <div class="flex items-center">
          <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-800 text-lg">
            {{ sale.user_name?.charAt(0) || 'U' }}
          </div>
          <div class="ml-3">
            <div class="text-sm font-medium text-gray-900">{{ sale.user.name }}</div>
            <div class="text-xs text-gray-500" v-if="sale.user.email">{{ sale.user.email }}</div>
          </div>
        </div>
      </div>

      <!-- Product Info -->
      <div>
        <h4 class="text-sm font-medium text-gray-500 mb-2">Produtos</h4>
        <div class="bg-gray-50 p-3 rounded-md">
          <div v-for="item in sale.items" :key="item.id">
            <div class="text-sm font-medium text-gray-900">{{item.quantity}} x {{ item.product.name }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ item.product.category.name }}</div>
          </div>
          <div class="mt-2 flex justify-between">
            <span class="text-sm text-gray-500">Valor:</span>
            <span class="text-sm font-medium text-gray-900">{{ formatCurrency(sale.amount) }}</span>
          </div>
        </div>
      </div>

      <!-- Status and Date -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <h4 class="text-sm font-medium text-gray-500 mb-2">Status</h4>
          <Badge :variant="sale.status">{{ sale.status }}</Badge>
        </div>
        <div>
          <h4 class="text-sm font-medium text-gray-500 mb-2">Data</h4>
          <div class="text-sm text-gray-900">{{ formatDate(sale.created_at) }}</div>
        </div>
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
import { SaleDetails, SalesData } from '../../api/dashboard/types';
import Spinner from '../atoms/Spinner.vue';
import Badge from '../atoms/Badge.vue';

const props = defineProps({
  sale: {
    type: Object as () => SaleDetails | SalesData,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  }
});

function getStatusVariant(status: string): string {
  switch (status) {
    case 'completed': return 'success';
    case 'pending': return 'warning';
    case 'cancelled': return 'danger';
    default: return 'secondary';
  }
}

function formatDate(dateString: string): string {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('pt-BR', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  }).format(date);
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(amount);
}

function formatKey(key: string): string {
  return key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}
</script> 