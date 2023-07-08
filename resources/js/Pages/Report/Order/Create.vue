<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TitlePage from '@/Components/TitlePage.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import { ref, watch } from 'vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import Pagination from '@/Components/Pagination.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';
import PaymentStatus from '@/Pages/Order/Partials/PaymentStatus.vue';
import { dateGMT } from '@/Composables/FormatDate.js'

const props = defineProps({
    filters: Object,
    orders: Object,
    success: String,
});

const numberDocument = ref(props.filters.numberDocument);
const date1 = ref(props.filters.date1);
const date2 = ref(props.filters.date2);

watch(numberDocument, value => {
    getResults();
});

watch(date1, value => {
    getResults();
});

watch(date2, value => {
    getResults();
});

const getResults = () => {
    router.get('/order_report', {
        numberDocument: numberDocument.value,
        date1: date1.value,
        date2: date2.value
    }, {
        preserveState: true,
        replace: true,
    });
}

const generateReport = () => {
    router.post(route('order.report.export'), {
        number_document: numberDocument.value,
        date_1: date1.value,
        date_2: date2.value,
    });
}

const tableTitles = ['ID', 'Código', 'Fecha de compra', 'Fecha de pago', 'Estado de pago', 'Total compra', 'Actualizado por última vez', 'Cliente'];

</script>
<template>
    <Head title="Reporte de ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Reporte de ordenes
                </TitlePage>
            </div>
        </template>
        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="w-full mt-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <input
                            v-model="numberDocument"
                            type="text"
                            class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            placeholder="Número de documento del usuario"
                        >
                    </div>
                    <div class="col-span-1 flex justify-center items-center gap-2">
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
                    <SuccessButton @click="generateReport()" class="col-span-1">
                        Generar reporte de ordenes
                    </SuccessButton>
                </div>
                <BasicTable v-if="orders.data.length > 0" :titles="tableTitles">
                    <TableRow v-for="order in orders.data">
                        <TableCol class="capitalize">
                            {{ order.id }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ order.code }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ dateGMT(order.purchase_date, -5) }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ dateGMT(order.payment_date, -5) }}
                        </TableCol>
                        <TableCol class="capitalize">
                            <PaymentStatus :order="order"/>
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ dateGMT(order.updated_at, -5) }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ `${order.first_name} ${order.second_name} ${order.surname} ${order.second_surname}` }}
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <NotFoundMessage v-else class="m-5"/>
                <Pagination class="my-6" :links="orders.links" />
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Orders report generated.'"
            title="¡Bien Hecho!"
            text="La exportación del reporte de ordenes está siendo procesada, una vez este proceso termine usted recibirá un correo electronico con el link de descarga del archivo."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
