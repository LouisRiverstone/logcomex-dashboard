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
  data: {
    type: Array as () => number[],
    required: true
  },
  backgroundColor: {
    type: Array as () => string[],
    default: () => ['#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe', '#f5f3ff']
  },
  className: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: ''
  },
  legend: {
    type: Boolean,
    default: true
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: 'pie',
    data: {
      labels: props.labels,
      datasets: [{
        data: props.data,
        backgroundColor: props.backgroundColor.length >= props.data.length 
          ? props.backgroundColor 
          : [...props.backgroundColor, ...Array(props.data.length - props.backgroundColor.length).fill('#8b5cf6')],
        borderColor: 'white',
        borderWidth: 1
      }]
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
          display: props.legend,
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              const label = context.label || '';
              const value = context.formattedValue;
              const total = context.dataset.data.reduce((a: number, b: number) => a + b, 0);
              const percentage = Math.round((context.raw as number) / total * 100);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  };
});
</script> 