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
      backgroundColor?: string;
      borderColor?: string;
      pointBackgroundColor?: string;
      pointBorderColor?: string;
      pointHoverBackgroundColor?: string;
      pointHoverBorderColor?: string;
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
    type: 'radar',
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        label: dataset.label,
        data: dataset.data,
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.2)',
        borderColor: dataset.borderColor || '#8b5cf6',
        pointBackgroundColor: dataset.pointBackgroundColor || '#8b5cf6',
        pointBorderColor: dataset.pointBorderColor || '#fff',
        pointHoverBackgroundColor: dataset.pointHoverBackgroundColor || '#fff',
        pointHoverBorderColor: dataset.pointHoverBorderColor || '#8b5cf6'
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
        r: {
          angleLines: {
            display: props.gridLines
          },
          grid: {
            display: props.gridLines
          },
          beginAtZero: true
        }
      }
    }
  };
});
</script> 