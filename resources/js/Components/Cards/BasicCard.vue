<script setup>
const props = defineProps({
    title: String,
});
</script>
<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-md shadow-md hover:shadow-lg scale-100 hover:scale-105 transition duration-200 flex flex-col gap-4"
    >
        <div class="px-5 py-3 text-gray-900 dark:text-white text-xl font-semibold border-b border-b-gray-400 dark:border-b-gray-200">
            {{ title }}
        </div>
        <div class="px-5 pb-4">
            <slot />
        </div>
    </div>
</template>
