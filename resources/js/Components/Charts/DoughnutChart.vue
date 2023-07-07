<script setup>
import { onMounted } from 'vue';

import Chart from 'chart.js/auto';

const props = defineProps({
    colors: Array,
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
    backgroundColor: props.colors,
    hoverOffset: 4
  }]
};

const config = {
  type: 'doughnut',
  data: data,
};

onMounted(() => {
    new Chart(document.getElementById(`${props.identificator}DoughnutChart`), config);
});
</script>
<template>
    <div>
        <canvas :id="`${identificator}DoughnutChart`"></canvas>
    </div>
</template>
