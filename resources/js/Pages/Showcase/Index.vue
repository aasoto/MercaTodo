<script setup>
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useSignedRoleStore } from '@/Store/SignedRole';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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

</script>
<template>
    <Head title="Vitrina de productos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1 flex justify-center items-center">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Vitrina de productos
                    </h2>
                </div>
                <div class="col-span-2">
                    <input
                        v-model="search"
                        type="text"
                        class="w-full px-5 py-[10px] border border-gray-400 rounded-md placeholder:italic"
                        placeholder="Buscar..."
                    >
                </div>
                <div class="col-span-1 relative group">
                    <button
                        id="dropdownDefaultButton"
                        class="w-full text-black bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
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
            </div>
        </template>
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="grid grid-cols-4 gap-4">
                <div
                    v-for="product in products.data"
                    class="bg-white rounded-md p-4 shadow-md hover:shadow-lg scale-100 hover:scale-105 transition duration-200 flex flex-col gap-4"
                >
                    <Link :href="route('showcase.show', product.slug)">
                        <img
                            class="w-full h-1/2 rounded object-cover object-center cursor-pointer"
                            :src="`../storage/images/products/${product.picture_1}`"
                            alt="product_image_1"
                        >
                        <h2 class="text-lg truncate font-medium hover:font-bold no-underline hover:underline cursor-pointer capitalize">
                            {{ product.name }}
                        </h2>
                        <div class="px-3 py-1 border border-gray-600 rounded-full w-max shadow-none hover:shadow scale-100 hover:scale-105 transition duration-200 cursor-pointer">
                            <h5 class="text-gray-600 text-sm font-light capitalize">
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
            </div>
            <Pagination class="my-6" :links="products.links" />
        </div>
    </AuthenticatedLayout>
</template>
