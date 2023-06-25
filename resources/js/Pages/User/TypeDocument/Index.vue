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

defineProps({
    typeDocuments: Object,
    success: String,
});

const tableTitles = ['ID', 'Código', 'Nombre', 'Acciones'];
</script>
<template>
    <Head title="Tipos de documento" />

    <AuthenticatedLayout>
        <template #header>
            <TitlePage>
                Listado de tipos de documentos
            </TitlePage>
        </template>

        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="flex justify-beetwen items-center w-full mt-5">
                    <Link :href="route('type_document.create')">
                        <SuccessButton class="w-full">Agregar tipo de documento</SuccessButton>
                    </Link>
                </div>
                <BasicTable :titles="tableTitles">
                    <TableRow v-for="typeDocument in typeDocuments">
                        <TableCol>
                            {{ typeDocument.id }}
                        </TableCol>
                        <TableCol>
                            {{ typeDocument.code }}
                        </TableCol>
                        <TableCol>
                            {{ typeDocument.name }}
                        </TableCol>
                        <TableCol class="flex justify-center items-center">
                            <Link :href="route('type_document.edit', typeDocument.id)">
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
            v-if="success === 'Type document created.'"
            title="¡Bien Hecho!"
            text="Tipo de documento guardado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertSuccess
            v-if="success === 'Type document updated.'"
            title="¡Bien Hecho!"
            text="Tipo de documento actualizado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
