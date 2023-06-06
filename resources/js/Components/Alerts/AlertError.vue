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
            <svg class="w-40 h-40 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
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
