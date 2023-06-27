<script setup>
import { onMounted } from 'vue';

import Chart from 'chart.js/auto';

const props = defineProps({
    colorBars: Array,
    colorBorderBars: Array,
    data: Array,
    identificator: String,
    label: String,
    labels: Array,
});

const data = {
  labels: props.labels,
  datasets: [{
    label: props.label,
    data: props.data,
    backgroundColor: props.colorBars,
    borderColor: props.colorBorderBars,
    borderWidth: 1
  }]
};

const config = {
    type: 'bar',
    data: data,
    options: {
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true
            }
        }
    }
};

onMounted(() => {
    new Chart(document.getElementById(`${props.identificator}BarChart`), config);
});
</script>
<template>
    <div>
        <canvas :id="`${identificator}BarChart`"></canvas>
    </div>
</template>
