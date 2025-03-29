<template>
  <BaseChart :chart-config="chartConfig" :class="className" />
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import BaseChart from './BaseChart.vue';
import { ChartConfiguration } from 'chart.js';

const props = defineProps({
  labels: {
    type: Array as () => string[], // ISO date strings
    required: true
  },
  datasets: {
    type: Array as () => {
      label: string;
      data: Array<{x: string, y: number}>; // x should be ISO date string
      backgroundColor?: string | string[];
      borderColor?: string | string[];
      borderWidth?: number;
      tension?: number;
      fill?: boolean;
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
  timeUnit: {
    type: String as () => 'day' | 'week' | 'month' | 'quarter' | 'year',
    default: 'day'
  },
  displayFormat: {
    type: String,
    default: undefined
  },
  maxSpan: {
    type: Object as () => {
      value: number;
      unit: 'day' | 'week' | 'month' | 'quarter' | 'year'
    },
    required: true
  },
  minSpan: {
    type: Object as () => {
      value: number;
      unit: 'day' | 'week' | 'month' | 'quarter' | 'year'
    },
    default: undefined
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
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.1)',
        borderColor: dataset.borderColor || '#8b5cf6',
        borderWidth: dataset.borderWidth || 2,
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
        },
        zoom: {
          zoom: {
            wheel: {
              enabled: true
            },
            pinch: {
              enabled: true
            },
            mode: 'x'
          },
          limits: {
            x: {
              max: 'original',
              min: 'original'
            }
          },
          pan: {
            enabled: true,
            mode: 'x'
          }
        }
      },
      scales: {
        x: {
          type: 'time',
          time: {
            unit: props.timeUnit,
            displayFormats: props.displayFormat ? { 
              [props.timeUnit]: props.displayFormat 
            } : undefined
          },
          grid: {
            display: props.gridLines
          },
          max: undefined,
          min: undefined,
          ticks: {
            maxRotation: 0,
            autoSkip: true
          },
          limits: {
            maxRange: props.maxSpan.value * (
              props.maxSpan.unit === 'day' ? 86400000 :
              props.maxSpan.unit === 'week' ? 604800000 :
              props.maxSpan.unit === 'month' ? 2592000000 :
              props.maxSpan.unit === 'quarter' ? 7776000000 :
              31536000000 // year
            ),
            minRange: props.minSpan ? props.minSpan.value * (
              props.minSpan.unit === 'day' ? 86400000 :
              props.minSpan.unit === 'week' ? 604800000 :
              props.minSpan.unit === 'month' ? 2592000000 :
              props.minSpan.unit === 'quarter' ? 7776000000 :
              31536000000 // year
            ) : undefined
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