<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    product: Object,
});
console.log(props.product);

const mainPicture = ref(props.product.picture_1);
const alternativePicture1 = ref(props.product.picture_2);
const alternativePicture2 = ref(props.product.picture_3);

const watchOtherPicture = (image) => {
    if (alternativePicture1.value === image) {
        alternativePicture1.value = mainPicture.value;
        mainPicture.value = image;
    }
    if (alternativePicture2.value === image) {
        alternativePicture2.value = mainPicture.value;
        mainPicture.value = image;
    }
}
</script>
<template>
    <Head :title="`MercaTodo - ${product.name}`" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-4xl text-gray-800 dark:text-gray-200 leading-tight capitalize">
                {{ product.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="grid grid-cols-3 gap-4 my-4">
                        <div class="col-span-2 grid grid-cols-7 gap-4">
                            <div class="col-span-5">
                                <img
                                    class="w-full"
                                    :src="`../images/products/${mainPicture}`"
                                    alt="product_photo_1"
                                >
                            </div>
                            <div class="col-span-2 flex flex-col gap-4">
                                <img
                                    v-show="alternativePicture1"
                                    @click="watchOtherPicture(alternativePicture1)"
                                    :src="`../images/products/${alternativePicture1}`"
                                    alt="product_photo_2"
                                >
                                <img
                                    v-show="alternativePicture2"
                                    @click="watchOtherPicture(alternativePicture2)"
                                    :src="`../images/products/${alternativePicture2}`"
                                    alt="product_photo_3"
                                >
                            </div>
                        </div>
                        <div class="col-span-1 flex flex-col gap-4">
                            <div class="px-6 py-2 border border-gray-600 rounded-full w-max mx-auto shadow-none hover:shadow scale-100 hover:scale-105 transition duration-200 cursor-pointer">
                                <h5 class="text-gray-600 text-xl font-light capitalize">
                                    {{ product.category }}
                                </h5>
                            </div>
                            <div class="text-right">
                                <h3 class="text-4xl font-bold">
                                    {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                </h3>
                                <h6 class="font-extralight italic">
                                    cada {{ product.unit }}
                                </h6>
                            </div>
                            <div
                                class="text-lg"
                                v-html="product.description"
                            ></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
