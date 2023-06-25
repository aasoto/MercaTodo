<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import BtnSmWarning from "@/Components/Buttons/BtnSmWarning.vue";
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import TitlePage from '@/Components/TitlePage.vue'

const props = defineProps({
    cities: Object,
    states: Object,
    success: String,
});

const tableTitles = ['ID', 'Ciudad', 'Estado', 'Acciones'];

</script>
<template>
<Head title="Ciudades para usuarios" />

<AuthenticatedLayout>
    <template #header>
        <TitlePage>
            Ciudades para usuarios
        </TitlePage>
    </template>

    <BasicBodyPage>
        <div class="flex flex-col justify-center items-center">
            <div class="flex justify-beetwen items-center w-full mt-5">
                <Link :href="route('city.create')">
                    <SuccessButton class="w-full">Agregar nueva ciudad</SuccessButton>
                </Link>
            </div>
            <BasicTable :titles="tableTitles">
                <TableRow v-for="city in cities">
                    <TableCol>
                        {{ city.id }}
                    </TableCol>
                    <TableCol>
                        {{ city.name }}
                    </TableCol>
                    <TableCol>
                        <template v-for="state in states">
                            <template v-if="state.id == city.state_id">
                                {{ state.name }}
                            </template>
                        </template>
                    </TableCol>
                    <TableCol class="flex justify-center items-center">
                        <Link :href="route('city.edit', city.id)">
                            <BtnSmWarning>
                                <PencilSquareIcon class="w-4 h-4"/>
                            </BtnSmWarning>
                        </Link>
                    </TableCol>
                </TableRow>
            </BasicTable>
        </div>
    </BasicBodyPage>
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
