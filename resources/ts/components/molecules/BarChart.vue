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
  horizontal: {
    type: Boolean,
    default: false
  },
  gridLines: {
    type: Boolean,
    default: true
  },
  legend: {
    type: Boolean,
    default: true
  },
  stacked: {
    type: Boolean,
    default: false
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: props.horizontal ? 'bar' : 'bar',
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        label: dataset.label,
        data: dataset.data,
        backgroundColor: dataset.backgroundColor || '#8b5cf6',
        borderColor: dataset.borderColor || 'transparent',
        borderWidth: dataset.borderWidth || 1
      }))
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: props.horizontal ? 'y' : 'x',
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
          stacked: props.stacked,
          grid: {
            display: props.gridLines
          }
        },
        y: {
          stacked: props.stacked,
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