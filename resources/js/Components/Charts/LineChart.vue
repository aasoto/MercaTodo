<script setup>
import { onMounted } from 'vue';

import Chart from 'chart.js/auto';

const props = defineProps({
    color: String,
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
    fill: false,
    borderColor: props.color,
    tension: 0.1
  }]
};

const config = {
  type: 'line',
  data: data,
};

onMounted(() => {
    new Chart(document.getElementById(`${props.identificator}LineChart`), config);
});
</script>
<template>
    <div>
        <canvas :id="`${identificator}LineChart`"></canvas>
    </div>
</template>
