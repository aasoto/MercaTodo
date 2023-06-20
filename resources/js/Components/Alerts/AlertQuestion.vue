<script setup>
import { QuestionMarkCircleIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

const props = defineProps({
    btnDelete: Boolean,
    btnClose: Boolean,
    close: Boolean,
    title: String,
    text: String,
    deleteRegister: Function,
    resetAlert: Function,
});

const closeModal = ref(props.close);

const setCloseModal = () => {
    props.resetAlert();
}

const disableBtnDelete = ref(false);

const setDisableBtnDelete = () => {
    disableBtnDelete.value = !disableBtnDelete.value;
    // setCloseModal();
    // closeModal.value = false;
    props.resetAlert();
}

</script>
<template>
    <div :class="closeModal ? 'hidden' : 'block'" class="absolute w-full bg-black/40 h-full z-20 top-0 flex justify-center">
        <div class="sticky top-20 w-1/2 h-max px-10 py-5 bg-white dark:bg-gray-700 rounded-lg flex flex-col justify-center items-center gap-5">
            <QuestionMarkCircleIcon class="w-40 h-40 text-blue-600"/>
            <h1 class="text-black dark:text-white text-3xl font-bold">
                {{ title }}
            </h1>
            <p class="text-black dark:text-white">
                {{ text }}
            </p>
            <div class="flex justify-center items-center gap-5">
                <button
                    v-if="btnDelete"
                    @click="setDisableBtnDelete(); deleteRegister();"
                    class="bg-gray-300 dark:bg-gray-500 hover:bg-gray-400 dark:hover:bg-gray-600 text-black dark:text-white px-5 py-2 rounded shadow scale-100 hover:scale-105 transition duration-200"
                    :disabled="disableBtnDelete"
                >
                    Eliminar
                </button>
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
