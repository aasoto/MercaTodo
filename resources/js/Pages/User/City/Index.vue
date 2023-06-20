<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';

const props = defineProps({
    cities: Object,
    states: Object,
    success: String,
});
</script>
<template>
<Head title="Ciudades para usuarios" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ciudades para usuarios
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex justify-beetwen items-center w-full mt-5">
                        <Link :href="route('city.create')">
                            <SuccessButton class="w-full">Agregar nueva ciudad</SuccessButton>
                        </Link>
                    </div>
                    <table class="w-full m-5 rounded-lg">
                        <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                            <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                ID
                            </th>
                            <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                Ciudad
                            </th>
                            <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                Estado
                            </th>
                            <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                Acciones
                            </th>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-400" v-for="city in cities">
                                <td class="px-3 py-3 text-black dark:text-white">
                                    {{ city.id }}
                                </td>
                                <td class="px-3 py-3 text-black dark:text-white">
                                    {{ city.name }}
                                </td>
                                <td class="px-3 py-3 text-black dark:text-white">
                                    <template v-for="state in states">
                                        <template v-if="state.id == city.state_id">
                                            {{ state.name }}
                                        </template>
                                    </template>
                                </td>
                                <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center">
                                    <Link :href="route('city.edit', city.id)">
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
                                Nombre
                            </th>
                            <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                Estado
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
        v-if="success === 'City created.'"
        title="¡Bien Hecho!"
        text="Ciudad agregada satisfactoriamente."
        :close="false"
        :btn-close="true"
    />
    <AlertSuccess
        v-if="success === 'City updated.'"
        title="¡Bien Hecho!"
        text="Ciudad actualizada satisfactoriamente."
        :close="false"
        :btn-close="true"
    />
</AuthenticatedLayout>
</template>
