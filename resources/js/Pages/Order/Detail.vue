<script setup>
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
    products: Object,
});
</script>
<template>
    <Head title="Detalle de orden" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Detalle de orden No. {{ order.id }}
                </h2>
                <div class="flex justify-center items-center gap-3">
                    <div class="rounded-md bg-blue-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Fecha: {{ order.purchase_date }}
                    </div>
                    <div v-if="order.payment_status == 'pending'" class="rounded-md bg-yellow-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: {{ order.payment_status }}
                    </div>
                    <div v-if="order.payment_status == 'paid'" class="rounded-md bg-green-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: {{ order.payment_status }}
                    </div>
                    <div class="rounded-md bg-blue-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Total: {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <table class="w-full m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Nombre
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Precio
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Cantidad
                                </th>
                                <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Unidad
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" class="border-b border-gray-400">
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.name }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white text-right capitalize">
                                        {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.quantity }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.unit }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Nombre
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Precio
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Cantidad
                                </th>
                                <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Unidad
                                </th>
                            </tfoot>
                        </table>
                        <Pagination class="my-6" :links="products.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
