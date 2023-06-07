<script setup>
import { ref } from 'vue';

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
            <svg class="w-40 h-40 text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
            </svg>
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
