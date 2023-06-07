<script setup>
import { ref, watch } from 'vue';

import { Head, Link, router, useForm } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import Pagination from '@/Components/Pagination.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import AlertQuestion from '@/Components/Alerts/AlertQuestion.vue';
import LoadingModal from '@/Components/LoadingModal.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';

const props = defineProps({
    filters: Object,
    products: Object,
    products_categories: Object,
    success: String,
});

const alertDelete = ref(false);

const form = useForm({
    slug: '',
});

const resetAlertDelete = () => {
    alertDelete.value = false;
}

const showAlertDelete = (slug) => {
    alertDelete.value = true;
    form.slug = slug;
    console.log(alertDelete.value, form.slug);
}

const showProduct = (slug) => {
    form.slug = slug;
    form.get(route('product.show', form.slug));
}

const deleteProduct = () => {
    console.log(form.slug);
    form.delete(route('product.destroy', form.slug));
}

const search = ref(props.filters.search);
const category = ref(props.filters.category);
const availability = ref(props.filters.availability);

watch(search, value => {
    getResults();
});

watch(category, value => {
    getResults();
});

const btnCategoryText = ref('Categoría');
const setCategory = (productCategory) => {
    if (productCategory) {
        category.value = productCategory;
        btnCategoryText.value = productCategory;
    } else {
        category.value = '';
        btnCategoryText.value = 'Categoría';
    }
}

watch(availability, value => {
    getResults();
});

const btnAvailabilityText = ref('Disponilidad');

const setAvailability = (value) => {
    switch (value) {
        case 'enabled':
            availability.value = true;
            btnAvailabilityText.value = 'Habilitados';
            break;
        case 'disabled':
            availability.value = false;
            btnAvailabilityText.value = 'Inhabilitados';
            break;
        default:
            availability.value = '';
            btnAvailabilityText.value = 'Disponilidad';
            break;
    }
}

const getResults = () => {
    router.get('/products', {
        search: search.value,
        category: category.value,
        availability: availability.value
    }, {
        preserveState: true,
        replace: true,
    });
}
</script>

<template>
    <Head title="Listado de productos" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Listado de productos
                </h2>
                <div class="relative group col-span-2">
                    <button
                        id="dropdownDefaultButton"
                        class="w-80 text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                        type="button"
                    >
                        Gestión de dependencias
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-80 dark:bg-gray-700 transition duration-200">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <Link
                                    :href="route('product_category.index')"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer"
                                >
                                    Categorías
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('unit.index')"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer"
                                >
                                    Unidades
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <div class="w-full mt-5 grid grid-cols-12 gap-4">
                            <Link :href="route('product.create')" class="col-span-3">
                                <SuccessButton class="w-full">Agregar productos</SuccessButton>
                            </Link>
                            <div class="col-span-5">
                                <input
                                    v-model="search"
                                    type="text"
                                    class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                                    placeholder="Buscar..."
                                >
                            </div>
                            <div class="relative group col-span-2">

                                <button
                                    id="dropdownDefaultButton"
                                    class="text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none w-44 px-5 py-3 text-center inline-flex items-center shadow-none group-hover:shadow transition duration-200"
                                    type="button"
                                >
                                    <span v-html="btnCategoryText" class="capitalize"></span>
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-44 dark:bg-gray-700 transition duration-200">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <span @click="setCategory()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                Todas
                                            </span>
                                        </li>
                                        <li v-for="product_category in products_categories">
                                            <span @click="setCategory(product_category.name)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                {{ product_category.name }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="relative group col-span-2">

                                <button
                                    id="dropdownDefaultButton"
                                    class="text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none w-44 px-5 py-3 text-center inline-flex items-center shadow-none group-hover:shadow transition duration-200"
                                    type="button"
                                >
                                    <span v-html="btnAvailabilityText"></span>
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-44 dark:bg-gray-700 transition duration-200">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <span @click="setAvailability()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                Todos
                                            </span>
                                        </li>
                                        <li>
                                            <span @click="setAvailability('enabled')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                Habilitado
                                            </span>
                                        </li>
                                        <li>
                                            <span @click="setAvailability('disabled')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                Inhabilitado
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <table v-if="products.data.length > 0" class="w-full m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Articulo
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Categoría
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Precio
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Unidad
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Stock
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Habilitado
                                </th>
                                <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-400" v-for="product in products.data">
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.name }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.category }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white text-right">
                                        {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.unit }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ product.stock }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <svg v-if="product.availability" class="w-8 h-8 text-green-600 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" />
                                        </svg>
                                        <svg v-else class="w-8 h-8 text-red-600 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center gap-2">
                                        <button @click="showProduct(product.slug)" class="bg-blue-600 rounded-md text-white p-1">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        <Link :href="route('product.edit', product.slug)">
                                            <button class="bg-yellow-400 rounded-md text-black p-1">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                </svg>
                                            </button>
                                        </Link>
                                        <button @click="showAlertDelete(product.slug)" class="bg-red-600 rounded-md text-white p-1">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Articulo
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Categoría
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Precio
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Unidad
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Stock
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Habilitado
                                </th>
                                <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </tfoot>
                        </table>
                        <NotFoundMessage v-else class="m-5"/>
                        <Pagination class="my-6" :links="products.links" />
                    </div>
                </div>
            </div>
        </div>
        <AlertSuccess
            v-if="success === 'Product created.'"
            title="¡Bien Hecho!"
            text="Producto guardado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertQuestion
            v-if="alertDelete"
            title="¿Desea eliminar este producto?"
            text="Si así lo desea de click en el botón eliminar."
            :delete-register="deleteProduct"
            :btn-delete="true"
            :close="false"
            :reset-alert="resetAlertDelete"
            :btn-close="true"
        />
        <LoadingModal v-show="form.processing"/>
        <AlertSuccess
            v-if="success === 'Product deleted.'"
            icon="success"
            title="¡Listo!"
            text="Producto eliminado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
