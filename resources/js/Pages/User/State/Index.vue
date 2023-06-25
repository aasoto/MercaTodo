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
import TitlePage from '@/Components/TitlePage.vue';

const props = defineProps({
    states: Object,
    success: String,
});

const tableTitles = ['ID', 'Nombre', 'Acciones'];

</script>
<template>
<Head title="Unidades de productos" />

<AuthenticatedLayout>
    <template #header>
        <TitlePage>
            Listado de Estados
        </TitlePage>
    </template>

    <BasicBodyPage>
        <div class="flex flex-col justify-center items-center">
            <div class="flex justify-beetwen items-center w-full mt-5">
                <Link :href="route('state.create')">
                    <SuccessButton class="w-full">Agregar nuevo estado</SuccessButton>
                </Link>
            </div>
            <BasicTable :titles="tableTitles">
                <TableRow v-for="state in states">
                    <TableCol>
                        {{ state.id }}
                    </TableCol>
                    <TableCol>
                        {{ state.name }}
                    </TableCol>
                    <TableCol>
                        <Link :href="route('state.edit', state.id)">
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
        v-if="success === 'State created.'"
        title="¡Bien Hecho!"
        text="Estado agregado satisfactoriamente."
        :close="false"
        :btn-close="true"
    />
    <AlertSuccess
        v-if="success === 'State updated.'"
        title="¡Bien Hecho!"
        text="Estado actualizado satisfactoriamente."
        :close="false"
        :btn-close="true"
    />
</AuthenticatedLayout>
</template>
