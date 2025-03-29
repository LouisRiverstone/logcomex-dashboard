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
      type: 'bar' | 'line';
      label: string;
      data: Array<{x: string, y: number}>; // x should be ISO date string
      backgroundColor?: string | string[];
      borderColor?: string | string[];
      borderWidth?: number;
      tension?: number;
      fill?: boolean;
      order?: number;
      yAxisID?: string;
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
  useSecondYAxis: {
    type: Boolean,
    default: false
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: 'bar', // Default type, individual datasets will override this
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        type: dataset.type,
        label: dataset.label,
        data: dataset.data,
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.7)',
        borderColor: dataset.borderColor || '#8b5cf6',
        borderWidth: dataset.borderWidth || (dataset.type === 'line' ? 2 : 1),
        tension: dataset.tension !== undefined ? dataset.tension : 0.4,
        fill: dataset.fill !== undefined ? dataset.fill : false,
        order: dataset.order || 0,
        yAxisID: dataset.yAxisID
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
        tooltip: {
          mode: 'index',
          intersect: false
        }
      },
      interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
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
          }
        },
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          beginAtZero: true,
          grid: {
            display: props.gridLines
          }
        },
        ...(props.useSecondYAxis && {
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            beginAtZero: true,
            grid: {
              display: false
            }
          }
        })
      }
    }
  };
});
</script> 