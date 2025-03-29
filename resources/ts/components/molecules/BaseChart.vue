<template>
  <div class="w-full h-full" :class="className">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import { ChartConfiguration } from 'chart.js';
import { enUS } from 'date-fns/locale';
import 'chartjs-adapter-date-fns';

const props = defineProps({
  chartConfig: {
    type: Object as () => ChartConfiguration,
    required: true
  },
  className: {
    type: String,
    default: ''
  }
});

const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chart: Chart | null = null;

onMounted(() => {
  if (chartCanvas.value) {
    chart = new Chart(chartCanvas.value, props.chartConfig);
  }
});

onUnmounted(() => {
  if (chart) {
    chart.destroy();
    chart = null;
  }
});

watch(() => props.chartConfig, (newConfig) => {
  if (chart) {
    chart.destroy();
  }
  
  if (chartCanvas.value) {
    chart = new Chart(chartCanvas.value, newConfig);
  }
}, { deep: true });

const updateChart = () => {
  if (chart) {
    chart.update();
  }
};

defineExpose({
  chart,
  updateChart
});
</script> 