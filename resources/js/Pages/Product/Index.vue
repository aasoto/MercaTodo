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
import { CheckIcon, ChevronDownIcon, PencilSquareIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';

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
                        <ChevronDownIcon class="w-4 h-4 ml-2"/>
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
                                    <ChevronDownIcon class="w-4 h-4 ml-2"/>
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
                                    <ChevronDownIcon class="w-4 h-4 ml-2"/>
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
                                        <CheckIcon v-if="product.availability" class="w-8 h-8 text-green-600 mx-auto"/>
                                        <XMarkIcon v-else class="w-8 h-8 text-red-600 mx-auto"/>
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center gap-2">
                                        <button @click="showProduct(product.slug)" class="bg-blue-600 rounded-md text-white p-1">
                                            <EyeIcon class="w-4 h-4"/>
                                        </button>
                                        <Link :href="route('product.edit', product.slug)">
                                            <button class="bg-yellow-400 rounded-md text-black p-1">
                                                <PencilSquareIcon class="w-4 h-4"/>
                                            </button>
                                        </Link>
                                        <button @click="showAlertDelete(product.slug)" class="bg-red-600 rounded-md text-white p-1">
                                            <TrashIcon class="w-4 h-4"/>
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
