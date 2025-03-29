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
      backgroundColor?: string | string[];
      borderColor?: string | string[];
      borderWidth?: number;
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
  },
  suggestedMin: {
    type: Number,
    default: undefined
  },
  suggestedMax: {
    type: Number,
    default: undefined
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: 'bar',
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        label: dataset.label,
        data: dataset.data,
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.7)',
        borderColor: dataset.borderColor || 'rgba(139, 92, 246, 1)',
        borderWidth: dataset.borderWidth || 1
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
          suggestedMin: props.suggestedMin,
          suggestedMax: props.suggestedMax,
          grid: {
            display: props.gridLines
          }
        }
      }
    }
  };
});
</script> 