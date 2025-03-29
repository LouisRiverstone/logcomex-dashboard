<template>
  <BaseChart :chart-config="chartConfig" :class="className" />
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import BaseChart from './BaseChart.vue';
import { ChartConfiguration } from 'chart.js';
import { enUS } from 'date-fns/locale';

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
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  // Transform data to ensure compatible format for Chart.js time scale
  const formattedDatasets = props.datasets.map(dataset => {
    // If data is not in the expected format for time scale
    const formattedData = Array.isArray(dataset.data) ? 
      dataset.data.map(item => {
        // Handle both object format and direct values
        if (typeof item === 'object' && item !== null && 'x' in item && 'y' in item) {
          return item; // Already in {x,y} format
        } else {
          // If just values are provided, pair with labels
          return {
            x: props.labels[dataset.data.indexOf(item as any)] || new Date().toISOString(),
            y: item as number
          };
        }
      }) : [];

    return {
      label: dataset.label,
      data: formattedData,
      backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.1)',
      borderColor: dataset.borderColor || '#8b5cf6',
      borderWidth: dataset.borderWidth || 2,
      tension: dataset.tension !== undefined ? dataset.tension : 0.4,
      fill: dataset.fill !== undefined ? dataset.fill : false
    };
  });

  return {
    type: 'line',
    data: {
      datasets: formattedDatasets
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
          type: 'time',
          time: {
            unit: props.timeUnit,
            displayFormats: props.displayFormat ? { 
              [props.timeUnit]: props.displayFormat 
            } : undefined,
            parser: 'yyyy-MM-dd'
          },
          adapters: {
            date: {
              locale: enUS
            }
          },
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