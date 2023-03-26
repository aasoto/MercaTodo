<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Error404 from "@/Pages/Errors/Error404.vue";

defineProps({
    roles: Array,
    users: Array,
    userRole: String,
});

</script>
<template>
    <template v-if="userRole == 'admin'">
        <Head title="Listado de usuarios" />

        <AuthenticatedLayout :userRole="userRole">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Listado de usuarios</h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex justify-center items-center bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <table class="w-11/12 m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
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
                                <tr class="border-b border-gray-400" v-for="user in users">
                                    <td class="px-3 py-3 text-black dark:text-white">
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
                                                {{ role.name }}
                                            </template>
                                        </template>
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center">
                                        <button class="bg-yellow-400 rounded-md text-black p-1">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
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
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </template>
    <template v-else>
        <Error404/>
    </template>
</template>
