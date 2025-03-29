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
      type: 'bar' | 'line' | 'bubble' | 'scatter';
      label: string;
      data: number[] | Array<{x: number, y: number, r?: number}>;
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
  useSecondYAxis: {
    type: Boolean,
    default: false
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: 'bar', // Default type, but individual datasets will override this
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        type: dataset.type,
        label: dataset.label,
        data: dataset.data,
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.7)',
        borderColor: dataset.borderColor || 'rgba(139, 92, 246, 1)',
        borderWidth: dataset.borderWidth || 1,
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
        intersect: false
      },
      scales: {
        x: {
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