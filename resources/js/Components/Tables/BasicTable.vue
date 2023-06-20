<script setup>
import TableHead from '@/Components/Tables/Basic/TableHead.vue';
import TableFooter from '@/Components/Tables/Basic/TableFooter.vue';

const props = defineProps({
    titles: Array,
});
</script>
<template>
    <table class="w-full m-5 rounded-lg">
        <TableHead :titles="titles"/>
        <tbody>
            <slot />
        </tbody>
        <TableFooter :titles="titles"/>
    </table>
</template>
