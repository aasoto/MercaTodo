<script setup>
const props = defineProps({
    titles: Array,
});
</script>
<template>
    <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
        <template v-for="(title, index) in titles">
            <th v-if="index == 0" class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                {{ title }}
            </th>
            <th v-else-if="index == (titles.length - 1)" class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                {{ title }}
            </th>
            <th v-else class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                {{ title }}
            </th>
        </template>
    </thead>
</template>
