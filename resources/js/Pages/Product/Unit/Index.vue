<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TitlePage from '@/Components/TitlePage.vue';

const props = defineProps({
    units: Object,
    success: String,
});

const tableTitles = [ 'ID', 'Codigo', 'Nombre', 'Acciones' ];
</script>
<template>
    <Head title="Unidades de productos" />

    <AuthenticatedLayout>
        <template #header>
            <TitlePage>
                Listado de unidades de productos
            </TitlePage>
        </template>

        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="flex justify-beetwen items-center w-full mt-5">
                    <Link :href="route('unit.create')">
                        <SuccessButton class="w-full">Agregar una unidad para productos</SuccessButton>
                    </Link>
                </div>
                <BasicTable :titles="tableTitles">
                    <TableRow v-for="unit in units">
                        <TableCol>
                            {{ unit.id }}
                        </TableCol>
                        <TableCol>
                            {{ unit.code }}
                        </TableCol>
                        <TableCol>
                            {{ unit.name }}
                        </TableCol>
                        <TableCol class="flex justify-center items-center">
                            <Link :href="route('unit.edit', unit.id)">
                                <button class="bg-yellow-400 rounded-md text-black p-1">
                                    <PencilSquareIcon class="w-4 h-4"/>
                                </button>
                            </Link>
                        </TableCol>
                    </TableRow>
                </BasicTable>
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Unit created.'"
            title="¡Bien Hecho!"
            text="Unidad agregada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertSuccess
            v-if="success === 'Unit updated.'"
            title="¡Bien Hecho!"
            text="Unidad actualizada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
