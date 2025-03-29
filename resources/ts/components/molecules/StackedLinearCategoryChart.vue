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
      stack?: string;
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
  chartType: {
    type: String as () => 'bar' | 'line',
    default: 'bar'
  },
  horizontal: {
    type: Boolean,
    default: false
  }
});

const chartConfig = computed<ChartConfiguration>(() => {
  return {
    type: props.chartType,
    data: {
      labels: props.labels,
      datasets: props.datasets.map(dataset => ({
        label: dataset.label,
        data: dataset.data,
        stack: dataset.stack || 'stack1',
        backgroundColor: dataset.backgroundColor || 'rgba(139, 92, 246, 0.7)',
        borderColor: dataset.borderColor || 'rgba(139, 92, 246, 1)',
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
        },
        tooltip: {
          mode: 'index'
        }
      },
      interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
      },
      scales: {
        x: {
          stacked: true,
          grid: {
            display: props.gridLines
          }
        },
        y: {
          stacked: true,
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