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
import DropdownButtonStrict from '@/Components/Buttons/DropdownButtonStrict.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    filters: Object,
    orders: Object,
    success: String,
});

const numberDocument = ref(props.filters.numberDocument);
const date1 = ref(props.filters.date1);
const date2 = ref(props.filters.date2);
const paymentStatus = ref(props.filters.paymentStatus);
const paymentStatusBtnLabel = ref('Estados de pago');
const minTotal = ref(props.filters.minTotal);
const maxTotal = ref(props.filters.maxTotal);

watch(numberDocument, value => {
    getResults();
});

watch(date1, value => {
    getResults();
});

watch(date2, value => {
    getResults();
});

watch(paymentStatus, value => {
    getResults();
});

watch(minTotal, value => {
    getResults();
});

watch(maxTotal, value => {
    getResults();
});

const selectPaymentStatus = (status, btnLabel) => {
    paymentStatus.value = status;
    paymentStatusBtnLabel.value = btnLabel;
}

const getResults = () => {
    router.get('/order_report', {
        numberDocument: numberDocument.value,
        date1: date1.value,
        date2: date2.value,
        paymentStatus: paymentStatus.value,
        minTotal: minTotal.value,
        maxTotal: maxTotal.value,
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
        payment_status: paymentStatus.value,
        min_total: minTotal.value,
        max_total: maxTotal.value,
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
                    <div class="col-span-2">
                        <input
                            v-model="numberDocument"
                            type="text"
                            class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            placeholder="Número de documento del usuario"
                        >
                    </div>
                    <DropdownButtonStrict class="col-span-1" :btn-label="paymentStatusBtnLabel">
                        <DropdownLink @click="selectPaymentStatus('paid', 'Pagado')">
                            Pagado
                        </DropdownLink>
                        <DropdownLink @click="selectPaymentStatus('pending', 'Pendiente')">
                            Pendiente
                        </DropdownLink>
                        <DropdownLink @click="selectPaymentStatus('canceled', 'Cancelado')">
                            Cancelado
                        </DropdownLink>
                        <DropdownLink @click="selectPaymentStatus('approved_partial', 'Pago incompleto')">
                            Pago incompleto
                        </DropdownLink>
                        <DropdownLink @click="selectPaymentStatus('verify_bank', 'Verificar con su banco')">
                            Verificar con su banco
                        </DropdownLink>
                        <DropdownLink @click="selectPaymentStatus('partial_expired', 'Pago expirado')">
                            Pago expirado
                        </DropdownLink>
                    </DropdownButtonStrict>
                    <SuccessButton @click="generateReport()" class="col-span-1">
                        Generar reporte de ordenes
                    </SuccessButton>
                    <div class="rounded-md border border-gray-300 p-1 col-span-1">
                        <InputLabel class="translate-x-7 -translate-y-3 bg-white dark:bg-gray-800 w-28 px-3">
                                Entre fechas
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
                    <div class="rounded-md border border-gray-300 p-1 col-span-1">
                        <InputLabel class="translate-x-7 -translate-y-3 bg-white dark:bg-gray-800 w-48 px-3">
                                Entre totales de compra
                        </InputLabel>
                        <div class="flex justify-center items-center gap-2">
                            <input
                                v-model="minTotal"
                                type="number"
                                placeholder="Total min."
                                class="px-2 py-[10px] w-32 bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                            <input
                                v-model="maxTotal"
                                type="number"
                                placeholder="Total max."
                                class="px-2 py-[10px] w-32 bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                        </div>
                    </div>
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
                            {{ dateGMT(order.purchase_date, 0) }}
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
