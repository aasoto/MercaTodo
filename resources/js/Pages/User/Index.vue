<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import { ref, watch } from 'vue';

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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Listado de usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <div class="grid grid-cols-12 mt-5 gap-4">
                            <Link class="col-span-2" :href="route('user.create')">
                                <SuccessButton class="w-full">Agregar usuario</SuccessButton>
                            </Link>
                            <div class="col-span-6">
                                <input
                                    v-model="search"
                                    type="text"
                                    class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                                    placeholder="Buscar..."
                                >
                            </div>
                            <div class="col-span-2 relative group">
                                <button
                                    id="dropdownDefaultButton"
                                    class="text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-md group-hover:rounded-t group-hover:rounded-b-none w-44 px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                                    type="button"
                                >
                                    <span>
                                        Habilitado
                                    </span>
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-44 dark:bg-gray-700 transition duration-200">
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
                            <div class="col-span-2 relative group">
                                <button
                                    id="dropdownDefaultButton"
                                    class="text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-md group-hover:rounded-t group-hover:rounded-b-none w-44 px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                                    type="button"
                                >
                                    <span>
                                        Rol
                                    </span>
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-44 dark:bg-gray-700 transition duration-200">
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
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                </svg>
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
                        <div v-else class="w-full m-5 px-10 py-5 border border-red-600 bg-red-300 rounded-lg text-red-700 text-center">
                            No se encontraron resultados con el criterio de busqueda dado.
                        </div>
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
