<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import BtnSmWarning from "@/Components/Buttons/BtnSmWarning.vue";
import DropdownButtonStrict from '@/Components/Buttons/DropdownButtonStrict.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import TitlePage from '@/Components/TitlePage.vue';

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

const tableTitles = ['Num. documento', 'Nombres', 'Correo electronico', 'Ciudad', 'Estado', 'Rol', 'Acciones'];

</script>
<template>
    <Head title="Listado de usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Listado de usuarios
                </TitlePage>
                <DropdownButtonStrict btn-label="Gestión de dependencias">
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
                </DropdownButtonStrict>
            </div>
        </template>
        <BasicBodyPage>
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
                    <DropdownButtonStrict btn-label="Habilitado" class="md:col-span-2">
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
                    </DropdownButtonStrict>
                    <DropdownButtonStrict btn-label="Rol" class="md:col-span-2">
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
                    </DropdownButtonStrict>
                </div>
                <BasicTable v-if="users.data.length > 0" :titles="tableTitles">
                    <TableRow v-for="user in users.data">
                        <TableCol>
                            {{ user.number_document }}
                        </TableCol>
                        <TableCol>
                            <template v-if="!user.second_name">
                                {{ user.second_name = "" }}
                            </template>
                            <template v-if="!user.second_surname">
                                {{ user.second_surname = "" }}
                            </template>
                            {{ `${user.first_name} ${user.second_name} ${user.surname} ${user.second_surname}` }}
                        </TableCol>
                        <TableCol>
                            {{ user.email }}
                        </TableCol>
                        <TableCol>
                            {{ user.city_name }}
                        </TableCol>
                        <TableCol>
                            {{ user.state_name }}
                        </TableCol>
                        <TableCol>
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
                        </TableCol>
                        <TableCol class="flex justify-center items-center">
                            <Link :href="route('user.edit', user.id)">
                                <BtnSmWarning>
                                    <PencilSquareIcon class="w-4 h-4"/>
                                </BtnSmWarning>
                            </Link>
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <NotFoundMessage v-else class="m-5"/>
                <Pagination class="my-6" :links="users.links" />
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'User created.'"
            title="¡Bien Hecho!"
            text="Usuario guardado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
