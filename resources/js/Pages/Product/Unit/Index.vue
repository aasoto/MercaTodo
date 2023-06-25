<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';

const props = defineProps({
    units: Object,
    success: String,
});
</script>
<template>
    <Head title="Unidades de productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Listado de unidades de productos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <div class="flex justify-beetwen items-center w-full mt-5">
                            <Link :href="route('unit.create')">
                                <SuccessButton class="w-full">Agregar una unidad para productos</SuccessButton>
                            </Link>
                        </div>
                        <table class="w-full m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    ID
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Codigo
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Nombre
                                </th>
                                <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-400" v-for="unit in units">
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ unit.id }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ unit.code }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ unit.name }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center">
                                        <Link :href="route('unit.edit', unit.id)">
                                            <button class="bg-yellow-400 rounded-md text-black p-1">
                                                <PencilSquareIcon class="w-4 h-4"/>
                                            </button>
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    ID
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Codigo
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Nombre
                                </th>
                                <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <AlertSuccess
            v-if="success === 'Unit created.'"
            title="¡Bien Hecho!"
            text="Unidad agregada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertSuccess
            v-if="success === 'Unit updated.'"
            title="¡Bien Hecho!"
            text="Unidad actualizada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
