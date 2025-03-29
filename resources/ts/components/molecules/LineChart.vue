<template>
  <BaseChart :chart-config="chartConfig" :class="className" />
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import BaseChart from './BaseChart.vue';
import { ChartConfiguration } from 'chart.js';

const props = defineProps({
  labels: {
    type: Array as () => string[],
    required: true
  },
  datasets: {
    type: Array as () => {
      label: string;
      data: number[];
      borderColor?: string;
      backgroundColor?: string;
      fill?: boolean;
      tension?: number;
    }[],
    required: true
  },
  className: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: ''
  },
  gridLines: {
    type: Boolean,
    default: true
  },
  legend: {
    type: Boolean,
    default: true
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: 'line',
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        label: dataset.label,
        data: dataset.data,
        borderColor: dataset.borderColor || '#8b5cf6',
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.1)',
        tension: dataset.tension !== undefined ? dataset.tension : 0.4,
        fill: dataset.fill !== undefined ? dataset.fill : false
      }))
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: !!props.title,
          text: props.title
        },
        legend: {
          display: props.legend
        }
      },
      scales: {
        x: {
          grid: {
            display: props.gridLines
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            display: props.gridLines
          }
        }
      }
    }
  };
});
</script> 