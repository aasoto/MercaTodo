<script setup>
import { ref, watch } from 'vue';

import { Head, Link, router } from '@inertiajs/vue3';

import { useSignedRoleStore } from '@/Store/SignedRole';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import NotFoundMessage from "@/Components/NotFoundMessage.vue";
const props = defineProps({
    filters: Object,
    products: Object,
    products_categories: Object,
    userRole: String,
});

const useSignedRole = useSignedRoleStore();
const { assignRole } = useSignedRole;
assignRole(props.userRole);

const search = ref(props.filters.search);

watch(search, value => {
    router.get('/showcase', {search: value}, {
        preserveState: true,
        replace: true,
    });
});

const category = ref(props.filters.category);

watch(category, value => {
    router.get('/showcase', {category: value}, {
        preserveState: true,
        replace: true,
    });
});

const setCategory = (productCategory) => {
    category.value = productCategory;
}

const minPrice = ref(props.filters.minPrice);
const maxPrice = ref(props.filters.maxPrice);

watch(minPrice, value => {
    if ((value > 0) && (maxPrice.value >= value)) {
        router.get('/showcase', {minPrice: value, maxPrice: maxPrice.value}, {
            preserveState: true,
            replace: true,
        });
    }
});

watch(maxPrice, value => {
    if ((minPrice.value > 0) && (value >= minPrice.value)) {
        router.get('/showcase', {minPrice: minPrice.value, maxPrice: value}, {
            preserveState: true,
            replace: true,
        });
    }
});

</script>
<template>
    <Head title="Vitrina de productos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <div class="col-span-1 flex justify-center items-center">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Vitrina de productos
                    </h2>
                </div>
                <div class="col-span-2">
                    <input
                        v-model="search"
                        type="text"
                        class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                        placeholder="Buscar..."
                    >
                </div>
                <div class="col-span-1 relative group">
                    <button
                        id="dropdownDefaultButton"
                        class="w-full text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                        type="button"
                    >
                        Categorías
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full dark:bg-gray-700 transition duration-200">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                            <li v-for="product_category in products_categories">
                                <span @click="setCategory(product_category.name)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                    {{ product_category.name }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-span-1 flex justify-center items-center gap-2">
                    <input
                        v-model="minPrice"
                        type="number"
                        placeholder="Valor min"
                        class="w-28 px-2 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                    >
                    <input
                        v-model="maxPrice"
                        type="number"
                        placeholder="Valor max"
                        class="w-28 px-2 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                    >
                </div>
            </div>
        </template>
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div
                    v-for="product in products.data"
                    class="bg-white dark:bg-gray-800 rounded-md p-4 shadow-md hover:shadow-lg scale-100 hover:scale-105 transition duration-200 flex flex-col gap-4"
                >
                    <Link :href="route('showcase.show', product.slug)">
                        <img
                            class="w-full h-56 rounded object-cover object-center cursor-pointer"
                            :src="`../storage/images/products/${product.picture_1}`"
                            alt="product_image_1"
                        >
                        <h2 class="text-lg truncate font-medium hover:font-bold no-underline hover:underline cursor-pointer capitalize">
                            {{ product.name }}
                        </h2>
                        <div class="px-3 py-1 border border-gray-600 dark:border-gray-500 rounded-full w-max shadow-none hover:shadow scale-100 hover:scale-105 transition duration-200 cursor-pointer">
                            <h5 class="text-gray-600 dark:text-gray-500 text-sm font-light capitalize">
                                {{ product.category }}
                            </h5>
                        </div>
                        <div>
                            <h1 class="text-2xl font-extrabold text-right">
                                {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                            </h1>
                            <h5 class="text-gray-400 italic text-sm text-right lowercase">
                                cada {{ product.unit }}
                            </h5>
                        </div>
                    </Link>
                </div>
                <NotFoundMessage
                    v-if="products.data.length <= 0"
                    class="col-span-1 md:col-span-2 lg:col-span-3 xl:col-span-4"
                />
            </div>
            <Pagination class="my-6" :links="products.links" />
        </div>
    </AuthenticatedLayout>
</template>
