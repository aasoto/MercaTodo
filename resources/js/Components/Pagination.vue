<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, k) in links" :key="k">
                <div v-if="link.url === null"  class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border dark:border-none bg-transparent dark:bg-gray-700 rounded" v-html="link.label" />
                <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border dark:border-none text-black dark:text-white dark:hover:text-black dark:bg-gray-700 rounded dark:hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-700 dark:bg-blue-500 text-white dark:text-blue-700 font-extrabold': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
