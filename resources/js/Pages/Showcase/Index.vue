<script setup>
import { ref, watch } from 'vue';

import { Head, Link, router } from '@inertiajs/vue3';

import { useSignedRoleStore } from '@/Store/SignedRole';
import { useCartStore } from '@/Store/Cart';

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

const useCart = useCartStore();
const { find } = useCart;

const btnCategoryLabel = ref('Categorías');

const search = ref(props.filters.search);
const category = ref(props.filters.category);
const minPrice = ref(props.filters.minPrice);
const maxPrice = ref(props.filters.maxPrice);

watch(search, value => {
    getResults();
});

watch(category, value => {
    getResults();
});

const setCategory = (productCategory) => {
    if (productCategory === 'Categorías') {
        category.value = '';
    } else {
        category.value = productCategory;
    }
    btnCategoryLabel.value = productCategory;
}

watch(minPrice, value => {
    getResults();
});

watch(maxPrice, value => {
    getResults();
});

const getResults = () => {
    if ((minPrice.value > 0) && (maxPrice.value >= minPrice.value)) {
        router.get('/showcase', {
            search: search.value,
            category: category.value,
            minPrice: minPrice.value,
            maxPrice: maxPrice.value
        }, {
            preserveState: true,
            replace: true,
        });
    } else {
        if ((!minPrice.value) && (!maxPrice.value))
        {
            router.get('/showcase', {
                search: search.value,
                category: category.value,
            }, {
                preserveState: true,
                replace: true,
            });
        }
    }
}

</script>
<template>
    <Head v-if="$page.props.auth.user" title="Vitrina de productos" />
    <Head v-else title="MercaTodo"/>

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
                        <span v-html="btnCategoryLabel" class="capitalize"></span>
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full dark:bg-gray-700 transition duration-200">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <span @click="setCategory('Categorías')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                    Ninguna
                                </span>
                            </li>
                        </ul>
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
                    <Link :href="route('showcase.show', product.slug)" class="relative">
                        <img
                            class="w-full h-56 rounded object-cover object-center cursor-pointer"
                            :src="`../storage/images/products/${product.picture_1}`"
                            alt="product_image_1"
                        >
                        <div class="absolute top-5 right-5">
                            <div
                                v-if="find(product.id)"
                                class="relative h-12 w-12 bg-black/40 flex items-center justify-center rounded-full"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="w-6 h-6 text-white"
                                >
                                    <path
                                        d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                    />
                                </svg>
                            </div>
                            <div v-if="product.stock == 0" class="relative h-12 w-12 bg-black/40 flex items-center justify-center rounded-md text-white">
                                AGOTADO
                            </div>
                        </div>
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
