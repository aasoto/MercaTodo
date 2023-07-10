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
import DropdownButtonStrict from '@/Components/Buttons/DropdownButtonStrict.vue';
import DropdownItem from '@/Components/Buttons/DropdownItem.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';

const props = defineProps({
    filters: Object,
    users: Object,
    states: Object,
    cities: Object,
    typeDocuments: Object,
    roles: Object,
    success: String,
});

const search = ref(props.filters.search);
const typeDocumentCode = ref(props.filters.typeDocument);
const verified = ref(props.filters.verified);
const enabled = ref(props.filters.enabled);
const role = ref(props.filters.role);
const date1 = ref(props.filters.date1);
const date2 = ref(props.filters.date2);
const stateId = ref(props.filters.stateId);
const cityId = ref(props.filters.cityId);

watch(search, value => {
    getResults();
});

watch(typeDocumentCode, value => {
    getResults();
});

watch(verified, value => {
    getResults();
});

watch(enabled, value => {
    getResults();
});

watch(role, value => {
    getResults();
});

watch(date1, value => {
    getResults();
});

watch(date2, value => {
    getResults();
});

watch(stateId, value => {
    getResults();
});

watch(cityId, value => {
    getResults();
});

const btnTypeDocumentText = ref('Tipo de documento');
const setTypeDocument = (code, name) => {
    if (code && name) {
        typeDocumentCode.value = code;
        btnTypeDocumentText.value = name;
    } else {
        typeDocumentCode.value = '';
        btnTypeDocumentText.value = 'Tipo de documento';
    }
}

const btnVerifiedText = ref('Verificación');
const setVerifiedEmail = (value) => {
    switch (value) {
        case 'verified':
            verified.value = 'true';
            btnVerifiedText.value = 'Solo verificados';
            break;
        case 'unverified':
            verified.value = 'false';
            btnVerifiedText.value = 'Solo sin verificar';
            break;
        default:
            verified.value = '';
            btnVerifiedText.value = 'Verificación';
            break;
    }
}

const btnEnabledText = ref('Habilitación');
const setEnabled = (value) => {
    switch (value) {
        case 'enabled':
            enabled.value = 'true';
            btnEnabledText.value = 'Solo habilitados';
            break;
        case 'disabled':
            enabled.value = 'false';
            btnEnabledText.value = 'Solo deshabilitados';
            break;
        default:
            enabled.value = '';
            btnEnabledText.value = 'Habilitación';
            break;
    }
}

const btnRoleText = ref('Rol');
const setRole = (id, name) => {
    if (id && name) {
        role.value = id;
        btnRoleText.value = name;
    } else {
        role.value = '';
        btnRoleText.value = 'Rol';
    }
}

const stateSelected = ref(0);

const showCities = (stateId) => {
    cityId.value = '';
    if (stateId) {
        stateSelected.value = stateId;
    } else {
        stateSelected.value = 0;
    }
}

const getResults = () => {
    router.get(route('user.report.create'), {
        search: search.value,
        typeDocument: typeDocumentCode.value,
        verified: verified.value,
        enabled: enabled.value,
        role: role.value,
        date1: date1.value,
        date2: date2.value,
        stateId: stateId.value,
        cityId: cityId.value,
    }, {
        preserveState: true,
        replace: true,
    });
}

const generateReport = () => {
    router.post(route('user.report.export'), {
        search: search.value,
        type_document: typeDocumentCode.value,
        verified: verified.value,
        enabled: enabled.value,
        role: role.value,
        date_1: date1.value,
        date_2: date2.value,
        state_id: stateId.value,
        city_id: cityId.value,
    });
}

const tableTitles = ['ID', 'Número documento', 'Nombre', 'Estado - Rol', 'Ciudad', 'Creado'];
</script>
<template>
    <Head title="Reporte de usuarios" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Reporte de usuarios
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
                    <DropdownButtonStrict :btn-label="btnTypeDocumentText" class="col-span-1">
                        <DropdownItem @click="setTypeDocument()">
                            Todos
                        </DropdownItem>
                        <DropdownItem
                            v-for="typeDocument in typeDocuments"
                            @click="setTypeDocument(typeDocument.code, typeDocument.name)"
                            :key="typeDocument.id"
                        >
                            {{ typeDocument.name }}
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <DropdownButtonStrict :btn-label="btnVerifiedText" class="col-span-1">
                        <DropdownItem @click="setVerifiedEmail()">
                            Todos
                        </DropdownItem>
                        <DropdownItem @click="setVerifiedEmail('verified')">
                            Correos verificados
                        </DropdownItem>
                        <DropdownItem @click="setVerifiedEmail('unverified')">
                            Correo sin verificar
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <DropdownButtonStrict :btn-label="btnEnabledText" class="col-span-1">
                        <DropdownItem @click="setEnabled()">
                            Todos
                        </DropdownItem>
                        <DropdownItem @click="setEnabled('enabled')">
                            Habilitados
                        </DropdownItem>
                        <DropdownItem @click="setEnabled('disabled')">
                            Inhabilitados
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <DropdownButtonStrict :btn-label="btnRoleText" class="col-span-1">
                        <DropdownItem @click="setRole()">
                            Todos
                        </DropdownItem>
                        <DropdownItem
                            v-for="role in roles"
                            @click="setRole(role.id, role.name)"
                            :key="role.id"
                        >
                            {{ role.name }}
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <div class="rounded-md border border-gray-300 p-1 col-span-1">
                        <InputLabel class="translate-x-7 -translate-y-3 bg-white dark:bg-gray-800 w-52 px-3">
                                Entre fechas de creación
                        </InputLabel>
                        <div class="flex justify-center items-center gap-2">
                            <input
                                v-model="date1"
                                type="date"
                                placeholder="Fecha inicio"
                                class="px-2 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                            <input
                                v-model="date2"
                                type="date"
                                placeholder="Fecha final"
                                class="px-2 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                        </div>
                    </div>
                    <div class="rounded-md border border-gray-300 p-1 col-span-2">
                        <InputLabel class="translate-x-7 -translate-y-3 bg-white dark:bg-gray-800 w-52 px-3">
                            Estado y ciudad
                        </InputLabel>
                        <div class="flex justify-center items-center gap-2">
                            <select
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                name="state_id"
                                id="state_id"
                                v-model="stateId"
                                @change="showCities(stateId)"
                            >
                                <option value="">Estados...</option>
                                <option
                                    v-for="state in states"
                                    :value="state.id"
                                    :key="state.id"
                                >
                                    {{ state.name }}
                                </option>
                            </select>
                            <template v-if="stateSelected !== 0">
                                <select
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    name="city_id"
                                    id="city_id"
                                    v-model="cityId"
                                >
                                    <option value="">Ciudades...</option>
                                    <template v-for="city in cities">
                                        <option
                                            v-if="city.state_id == stateSelected"
                                            :value="city.id"
                                            :key="city.id"
                                        >
                                            {{ city.name }}
                                        </option>
                                    </template>
                                </select>
                            </template>
                        </div>
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
