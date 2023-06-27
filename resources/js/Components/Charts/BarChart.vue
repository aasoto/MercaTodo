<script setup>
import { onMounted } from 'vue';

import Chart from 'chart.js/auto';

const config = {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

onMounted(() => {
    new Chart(document.getElementById('BarChart'), config);
});
</script>
<template>
    <div>
        <canvas id="BarChart"></canvas>
    </div>
</template>
