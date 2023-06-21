<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { PencilSquareIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';

import TitlePage from '@/Components/TitlePage.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import BtnSmWarning from '@/Components/Buttons/BtnSmWarning.vue';

const props = defineProps({
    categories: Object,
    success: String,
});

const tableTitles = ['ID', 'Nombre', 'Acciones'];
</script>
<template>
    <Head title="Categorías de productos" />

    <AuthenticatedLayout>
        <template #header>
            <TitlePage>
                Listado de categorías de productos
            </TitlePage>
        </template>

        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="flex justify-beetwen items-center w-full mt-5">
                    <Link :href="route('product_category.create')">
                        <SuccessButton class="w-full">Agregar categoría de productos</SuccessButton>
                    </Link>
                </div>
                <BasicTable :titles="tableTitles">
                    <TableRow v-for="category in categories">
                        <TableCol>
                            {{ category.id }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ category.name }}
                        </TableCol>
                        <TableCol class="flex justify-center items-center">
                            <Link :href="route('product_category.edit', category.id)">
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
            v-if="success === 'Product category created.'"
            title="¡Bien Hecho!"
            text="Categoría de producto agregada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertSuccess
            v-if="success === 'Product category updated.'"
            title="¡Bien Hecho!"
            text="Categoría de producto actualizada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
