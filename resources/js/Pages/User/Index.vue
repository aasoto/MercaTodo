<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { ChevronDownIcon, PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';

const props = defineProps({
    filters: Object,
    roleSearch: String,
    roles: Array,
    success: String,
    users: Object,
});

const search = ref(props.filters.search);
const enabled = ref(props.filters.enabled);

watch(search, value => {
    router.get(`/user/${props.roleSearch}`, {search: value}, {
        preserveState: true,
        replace: true,
    });
});

watch(enabled, value => {
    router.get(`/user/${props.roleSearch}`, {enabled: value}, {
        preserveState: true,
        replace: true,
    });
});

const setEnabled = (value) => {
    if (value) {
        enabled.value = true;
    } else {
        enabled.value = false;
    }
}

</script>
<template>
    <Head title="Listado de usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Listado de usuarios
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
                                    :href="route('city.index')"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer"
                                >
                                    Ciudades
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('state.index')"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer"
                                >
                                    Estados
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('type_document.index')"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer"
                                >
                                    Tipo de documento
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
                        <div class="w-full grid grid-cols-1 md:grid-cols-12 mt-5 gap-4">
                            <Link class="cols-span-1 md:col-span-2" :href="route('user.create')">
                                <SuccessButton class="w-full">Agregar usuario</SuccessButton>
                            </Link>
                            <div class="col-span-1 md:col-span-6">
                                <input
                                    v-model="search"
                                    type="text"
                                    class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                                    placeholder="Buscar..."
                                >
                            </div>
                            <div class="col-span-1 md:col-span-2 relative group">
                                <button
                                    id="dropdownDefaultButton"
                                    class="w-full md:w-44 text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-md group-hover:rounded-t group-hover:rounded-b-none px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                                    type="button"
                                >
                                    <span>
                                        Habilitado
                                    </span>
                                    <ChevronDownIcon class="w-4 h-4 ml-2"/>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full md:w-44 dark:bg-gray-700 transition duration-200">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                        <li @click="setEnabled(true)">
                                            <span class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer">
                                                Sí
                                            </span>
                                        </li>
                                        <li @click="setEnabled(false)">
                                            <span class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer">
                                                No
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-span-1 md:col-span-2 relative group">
                                <button
                                    id="dropdownDefaultButton"
                                    class="text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-md group-hover:rounded-t group-hover:rounded-b-none w-full md:w-44 px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                                    type="button"
                                >
                                    <span>
                                        Rol
                                    </span>
                                    <ChevronDownIcon class="w-4 h-4 ml-2"/>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full md:w-44 dark:bg-gray-700 transition duration-200">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <Link :href="route('user.index', 'admin')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                Administrador
                                            </Link>
                                        </li>
                                        <li>
                                            <Link :href="route('user.index', 'client')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                Cliente
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <table v-if="users.data.length > 0" class="w-full m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Num. documento
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Nombres
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Correo electronico
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Ciudad
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Estado
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Rol
                                </th>
                                <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-400" v-for="user in users.data">
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ user.number_document }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        <template v-if="!user.second_name">
                                            {{ user.second_name = "" }}
                                        </template>
                                        <template v-if="!user.second_surname">
                                            {{ user.second_surname = "" }}
                                        </template>
                                        {{ `${user.first_name} ${user.second_name} ${user.surname} ${user.second_surname}` }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ user.city_name }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        {{ user.state_name }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white">
                                        <template v-for="role in roles">
                                            <template v-if="role.id == user.role_id">
                                                <span v-if="!user.email_verified_at" class="text-yellow-600"> ● </span>
                                                <template v-else>
                                                    <span v-if="user.enabled" class="text-green-600"> ● </span>
                                                    <span v-else class="text-red-600"> ● </span>
                                                </template>
                                                {{ ` ${role.name}` }}
                                            </template>
                                        </template>
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center">
                                        <Link :href="route('user.edit', user.id)">
                                            <button class="bg-yellow-400 rounded-md text-black p-1">
                                                <PencilSquareIcon class="w-4 h-4"/>
                                            </button>
                                        </Link>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Num. documento
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Nombres
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Correo electronico
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Ciudad
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Estado
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Rol
                                </th>
                                <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </tfoot>
                        </table>
                        <NotFoundMessage v-else class="m-5"/>
                        <Pagination class="my-6" :links="users.links" />
                    </div>
                </div>
            </div>
        </div>
        <AlertSuccess
            v-if="success === 'User created.'"
            title="¡Bien Hecho!"
            text="Usuario guardado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
