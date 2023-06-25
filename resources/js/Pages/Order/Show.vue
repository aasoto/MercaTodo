<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

import { useCartStore } from '@/Store/Cart';

import { ChevronDownIcon, CreditCardIcon, LinkIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import AlertError from '@/Components/Alerts/AlertError.vue';
import InfoButton from '@/Components/Buttons/InfoButton.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import Pagination from '@/Components/Pagination.vue';
import TitlePage from '@/Components/TitlePage.vue';
import PaymentStatusDetail from '@/Pages/Order/Partials/PaymentStatusDetail.vue';
import MulticolorInfo from '@/Components/Infos/MulticolorInfo.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import DropdownButtonFlexible from '@/Components/Buttons/DropdownButtonFlexible.vue';
import DropdownItemBox from '@/Components/Buttons/DropdownItemBox.vue';
import DropdownItem from '@/Components/Buttons/DropdownItem.vue';

const props = defineProps({
    order: Object,
    paymentMethods: Object,
    products: Object,
    success: String,
});

const useCart = useCartStore();

const { emptyCart } = useCart;

if (props.success == 'Order created.' || props.success == 'Payment link updated.') {
    emptyCart();
}

const totalPrice = (price, quantity) => {
    return price * quantity;
}

const openWebcheckout = (code) => {
    router.get(route('payment.show', code));
}

const expirationDate = (date) => {
    var res = new Date(date);
    res.setDate(res.getDate() + 1);
    return res;
}

const canPay = ref(false);

const date1 = new Date();
const date2 = new Date(expirationDate(props.order.updated_at));

if (date1 < date2) {
    canPay.value = true;
}

const form = useForm({
    id: props.order.id,
    payment_method: '',
});

const btnPaymentMethodLabel = ref('Metodo de pago');
const showAlertPaymentMethod = ref(false);

const setPaymentMethod = (code, name) => {
    if (code === 'NONE') {
        form.payment_method = '';
    } else {
        form.payment_method = code;
    }
    btnPaymentMethodLabel.value = name;
}

const generateNewURLWebcheckout = () => {
    if (form.payment_method == '') {
        showAlertPaymentMethod.value = true;
    } else {
        router.post(route('payment.update', form.id),{
            _method: 'patch',
            id: form.id,
            payment_method: form.payment_method,
        });
    }
}

const localDate = (date) => {
    const dt = new Date(date);
    return dt.toLocaleString();
}

const tableTitles = [ 'Nombre', 'Unidad', 'Cantidad', 'Valor unitario', 'Valor total' ];
</script>
<template>
    <Head title="Detalle de orden" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Detalle de orden No. {{ order.id }}
                </TitlePage>
                <div class="flex justify-center items-center gap-3">
                    <MulticolorInfo color="blue">
                        Fecha: {{ localDate(order.created_at) }}
                    </MulticolorInfo>
                    <PaymentStatusDetail :payment-status="order.payment_status" :url="order.url"/>
                    <MulticolorInfo color="blue">
                        Total orden: {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                    </MulticolorInfo>
                </div>
            </div>
        </template>

        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <BasicTable :titles="tableTitles">
                    <TableRow v-for="product in products.data">
                        <TableCol class="capitalize">
                            {{ product.name }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ product.unit }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ product.quantity }}
                        </TableCol>
                        <TableCol class="text-right capitalize">
                            {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                        </TableCol>
                        <TableCol class="text-right capitalize">
                            {{ totalPrice(product.price, product.quantity).toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <Pagination class="my-6" :links="products.links" />
                <div class="w-full flex justify-end items-center gap-5 mb-5">
                    <DropdownButtonFlexible :btn-label="btnPaymentMethodLabel" v-if="(order.payment_status == 'pending' && !order.url) || order.payment_status == 'canceled'">
                        <DropdownItemBox>
                            <DropdownItem @click="setPaymentMethod('NONE', 'Metodo de pago')">
                                Ninguno
                            </DropdownItem>
                        </DropdownItemBox>
                        <DropdownItemBox>
                            <DropdownItem v-for="paymentMethod in paymentMethods" @click="setPaymentMethod(paymentMethod.code, paymentMethod.name)">
                                {{ paymentMethod.name }}
                            </DropdownItem>
                        </DropdownItemBox>
                    </DropdownButtonFlexible>
                    <SuccessButton v-if="(canPay == true) && (order.payment_status == 'pending'  || order.payment_status == 'approved_partial') && order.url" @click="openWebcheckout(order.code)" class="flex gap-4">
                        <CreditCardIcon class="w-6 h-6"/>
                        <span v-if="order.payment_status == 'pending'">
                            Pagar orden
                        </span>
                        <span v-else>
                            Completar pago
                        </span>
                    </SuccessButton>
                    <InfoButton v-else-if="(order.payment_status == 'pending')  || (order.payment_status == 'canceled')" @click="generateNewURLWebcheckout()" class="flex gap-4">
                        <LinkIcon class="w-6 h-6"/>
                        Generar nuevo link de pago
                    </InfoButton>
                    <SuccessButton v-if="order.payment_status == 'waiting'" @click="openWebcheckout(order.code)" class="flex gap-4">
                        <CreditCardIcon class="w-6 h-6"/>
                        Ver estado de la transacción
                    </SuccessButton>
                </div>
            </div>
        </BasicBodyPage>
        <AlertError
            v-if="success === 'Payment error.'"
            title="Error de servicio externo"
            text="Intente el proceso de nuevo."
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
