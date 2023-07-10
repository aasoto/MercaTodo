<script setup>
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TitlePage from '@/Components/TitlePage.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import { dateGMT } from '@/Composables/FormatDate.js'

const props = defineProps({
    filters: Object,
    users: Object,
    states: Object,
    cities: Object,
    typeDocument: Object,
    roles: Object,
    success: String,
});

const search = ref(props.filters.search);

watch(search, value => {
    getResults();
});

const getResults = () => {
    router.get(route('user.report.create'), {
        search: search.value,
    });
}

const generateReport = () => {
    router.post(route('user.report.export'), {
        search: search.value,
    });
}

const tableTitles = ['ID', 'Número documento', 'Nombre', 'Estado - Rol', 'Ciudad', 'Creado'];
</script>
<template>
    <Head title="Reporte de productos" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Reporte de productos
                </TitlePage>
            </div>
        </template>
        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="w-full mt-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <input
                            v-model="search"
                            type="text"
                            class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            placeholder="Buscar..."
                        >
                    </div>
                    <SuccessButton @click="generateReport()" class="col-span-1">
                        Generar reporte de usuarios
                    </SuccessButton>
                </div>
                <BasicTable v-if="users.data.length > 0" :titles="tableTitles">
                    <TableRow v-for="user in users.data">
                        <TableCol class="capitalize">
                            {{ user.id }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ `${user.type_document} - ${user.number_document}` }}
                        </TableCol>
                        <TableCol class="capitalize">
                            <template v-if="!user.second_name">
                                {{ user.second_name = "" }}
                            </template>
                            <template v-if="!user.second_surname">
                                {{ user.second_surname = "" }}
                            </template>
                            {{ `${user.first_name} ${user.second_name} ${user.surname} ${user.second_surname}` }}
                        </TableCol>
                        <TableCol class="capitalize">
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
                        <TableCol class="capitalize">
                            {{ `${user.city_name} - ${user.state_name}` }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ dateGMT(user.created_at, -5) }}
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <NotFoundMessage v-else class="m-5"/>
                <Pagination class="my-6" :links="users.links" />
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Users report generated.'"
            title="¡Bien Hecho!"
            text="La exportación del reporte de usuarios está siendo procesada, una vez este proceso termine usted recibirá un correo electronico con el link de descarga del archivo."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
