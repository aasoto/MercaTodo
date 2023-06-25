<script setup>
import { ref } from 'vue';

import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    btnClose: Boolean,
    close: Boolean,
    title: String,
    text: String,
});

const closeModal = ref(props.close);

const setCloseModal = () => {
    closeModal.value = !closeModal.value;
}
</script>
<template>
    <div :class="closeModal ? 'hidden' : 'block'" class="absolute w-full bg-black/40 h-full z-20 top-0 flex justify-center">
        <div class="sticky top-20 w-1/2 h-max px-10 py-5 bg-white dark:bg-gray-700 rounded-lg flex flex-col justify-center items-center gap-5">
            <ExclamationTriangleIcon class="w-40 h-40 text-yellow-300"/>
            <h1 class="text-black dark:text-white text-3xl font-bold">
                {{ title }}
            </h1>
            <p class="text-black dark:text-white">
                {{ text }}
            </p>
            <div class="flex justify-center items-center gap-5">
                <button
                    v-if="btnClose"
                    @click="setCloseModal()"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded shadow scale-100 hover:scale-105 transition duration-200"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</template>
