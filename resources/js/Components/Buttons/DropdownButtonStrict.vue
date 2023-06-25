<script setup>
import { ChevronDownIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    btnLabel: String,
});
</script>
<template>
    <div class="relative group">
        <button
            id="dropdownDefaultButton"
            class="w-full text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
            type="button"
        >
            <span v-html="btnLabel" class="capitalize"></span>
            <ChevronDownIcon class="w-4 h-4 ml-2"/>
        </button>
        <!-- Dropdown menu -->
        <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full dark:bg-gray-700 transition duration-200">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <slot />
            </ul>
        </div>
    </div>
</template>
