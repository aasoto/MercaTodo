<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

import { EyeIcon, LinkIcon } from '@heroicons/vue/24/outline';
import { ChevronDownIcon, CreditCardIcon } from '@heroicons/vue/24/solid';

import AlertError from '@/Components/Alerts/AlertError.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TitlePage from '@/Components/TitlePage.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import PaymentStatus from '@/Pages/Order/Partials/PaymentStatus.vue';
import BtnMdPrimary from '@/Components/Buttons/BtnMdPrimary.vue';
import BtnMdSuccess from '@/Components/Buttons/BtnMdSuccess.vue';
import BtnMdInfo from '@/Components/Buttons/BtnMdInfo.vue';
import DropdownMdButtonFlexible from '@/Components/Buttons/DropdownMdButtonFlexible.vue';
import DropdownItemBox from '@/Components/Buttons/DropdownItemBox.vue';
import DropdownItem from '@/Components/Buttons/DropdownItem.vue';
import PrimaryInfoXL from '@/Components/Infos/PrimaryInfoXL.vue';

const props = defineProps({
    orders: Object,
    paymentMethods: Object,
    success: String,
});

const form = useForm({
    id: '',
});

const showOrderDetail = (id) => {
    form.id = id;
    form.get(route('order.show', form.id));
}

const openWebcheckout = (code) => {
    router.get(route('payment.show', code));
}

const expirationDate = (date) => {
    let res = new Date(date);
    res.setDate(res.getDate() + 1);

    const date1 = new Date();
    const date2 = new Date(res);

    if (date1 < date2) {
        return true;
    } else {
        return false;
    }
}

const dateGMT_5 = (date) => {
    let res = new Date(date);
    res.setHours(res.getHours() - 5);

    const year = res.getFullYear();
    const month = res.getMonth() + 1;
    const day = res.getDate();
    const hours = res.getHours();
    const minutes = res.getMinutes();
    const seconds = res.getSeconds();
    return year+'-'+month+'-'+day+' '+hours+':'+minutes+':'+seconds;
}

const btnPaymentMethodLabel = ref('Metodo de pago');
const paymentMethod = ref('');
const showAlertPaymentMethod = ref(false);
const regenerateLinkId = ref('');

const setPaymentMethod = (code, name, id) => {
    if (code === 'NONE') {
        paymentMethod.value = '';
    } else {
        paymentMethod.value = code;
        regenerateLinkId.value = id;
    }
    btnPaymentMethodLabel.value = name;
}

const generateNewURLWebcheckout = (id) => {
    if (paymentMethod.value == '') {
        showAlertPaymentMethod.value = true;
    } else {
        router.post(route('payment.update', id),{
            _method: 'patch',
            id: id,
            payment_method: paymentMethod.value,
        });
    }
}

const tableTitles = [ 'ID', 'Fecha de la compra', 'Fecha del pago', 'Estado de pago', 'Total de la compra', 'Acciones'];

</script>
<template>
    <Head title="Mis ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <TitlePage>
                Mis ordenes
            </TitlePage>
        </template>
        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <BasicTable v-if="orders.data.length != 0" :titles="tableTitles">
                    <TableRow v-for="order in orders.data" :key="order.id">
                        <TableCol class="capitalize">
                            {{ order.id }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ dateGMT_5(order.purchase_date) }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ order.payment_date ? dateGMT_5(order.payment_date) : '' }}
                        </TableCol>
                        <TableCol class="capitalize">
                            <PaymentStatus :order="order"/>
                        </TableCol>
                        <TableCol class="text-right capitalize">
                            {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                        </TableCol>
                        <TableCol class="capitalize flex justify-center items-center gap-3">
                            <BtnMdPrimary @click="showOrderDetail(order.id)">
                                <EyeIcon class="w-4 h-4"/>
                                <span>
                                    Detalles
                                </span>
                            </BtnMdPrimary>
                            <DropdownMdButtonFlexible
                                v-if="(order.payment_status == 'pending' && !order.url) || order.payment_status == 'canceled'"
                                :btn-label="regenerateLinkId == order.id ? btnPaymentMethodLabel : 'Metodo de pago'"
                            >
                                <DropdownItemBox>
                                    <DropdownItem @click="setPaymentMethod('NONE', 'Metodo de pago', order.id)">
                                        Ninguno
                                    </DropdownItem>
                                </DropdownItemBox>
                                <DropdownItemBox>
                                    <DropdownItem v-for="paymentMethod in paymentMethods" @click="setPaymentMethod(paymentMethod.code, paymentMethod.name, order.id)">
                                        {{ paymentMethod.name }}
                                    </DropdownItem>
                                </DropdownItemBox>
                            </DropdownMdButtonFlexible>
                            <BtnMdSuccess v-if="(order.payment_status == 'pending' || order.payment_status == 'approved_partial') && expirationDate(order.updated_at) && order.url" @click="openWebcheckout(order.code)">
                                <CreditCardIcon class="w-4 h-4"/>
                                <span v-if="order.payment_status == 'pending'">
                                    Pagar orden
                                </span>
                                <span v-else>
                                    Completar pago
                                </span>
                            </BtnMdSuccess>
                            <BtnMdInfo v-else-if="(order.payment_status == 'pending' || order.payment_status == 'canceled') && paymentMethod && (regenerateLinkId == order.id)" @click="generateNewURLWebcheckout(order.id)">
                                <LinkIcon class="w-6 h-6"/>
                                <span v-if="!order.url">
                                    Generar link de pago
                                </span>
                                <span v-else>
                                    Generar nuevo link de pago
                                </span>
                            </BtnMdInfo>
                            <BtnMdSuccess v-if="(order.payment_status == 'waiting') && order.url" @click="openWebcheckout(order.code)">
                                <CreditCardIcon class="w-4 h-4"/>
                                Ver estado transacción
                            </BtnMdSuccess>
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <PrimaryInfoXL v-else>
                    Listado de ordenes vacio
                </PrimaryInfoXL>
                <Pagination class="my-6" :links="orders.links" />
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Order created.'"
            icon="success"
            title="¡Listo!"
            text="Orden enviada satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertError
            v-if="showAlertPaymentMethod === true"
            title="Sin metodo de pago"
            text="Seleccione un metodo de pago"
            :close="false"
            :btn-close="true"
            @click="() => showAlertPaymentMethod = false"
        />
    </AuthenticatedLayout>
</template>
