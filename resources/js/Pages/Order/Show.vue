<script setup>
import AlertError from '@/Components/Alerts/AlertError.vue';
import InfoButton from '@/Components/Buttons/InfoButton.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useCartStore } from '@/Store/Cart';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    order: Object,
    products: Object,
    success: String,
});

const useCart = useCartStore();

const { emptyCart } = useCart;

if (props.success == 'Order created.') {
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
});

const generateNewURLWebcheckout = () => {
    router.post(route('payment.update', form.id),{
        _method: 'patch',
        id: form.id,
    });
}

const localDate = (date) => {
    const dt = new Date(date);
    return dt.toLocaleString();
}
</script>
<template>
    <Head title="Detalle de orden" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Detalle de orden No. {{ order.id }}
                </h2>
                <div class="flex justify-center items-center gap-3">
                    <div class="rounded-md bg-blue-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Fecha: {{ localDate(order.created_at) }}
                    </div>
                    <div v-if="order.payment_status == 'canceled'" class="rounded-md bg-red-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: CANCELADO
                    </div>
                    <div v-if="order.payment_status == 'paid'" class="rounded-md bg-green-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: PAGADO
                    </div>
                    <div v-if="(order.payment_status == 'pending') && order.url" class="rounded-md bg-yellow-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: PENDIENTE
                    </div>
                    <div v-else-if="order.payment_status == 'pending'" class="rounded-md bg-blue-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: SIN LINK DE PAGO
                    </div>
                    <div v-if="order.payment_status == 'waiting'" class="rounded-md bg-purple-300 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: PAGO POR CONFIRMAR
                    </div>
                    <div v-if="order.payment_status == 'verify_bank'" class="rounded-md bg-orange-300 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Estado: VERIFICAR TRANSACCIÓN CON SU ENTIDAD BANCARIA
                    </div>
                    <div class="rounded-md bg-blue-200 px-10 py-2 text-gray-900 dark:text-white text-md font-bold">
                        Total orden: {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <table class="w-full m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Nombre
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Unidad
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Cantidad
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Valor unitario
                                </th>
                                <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Valor total
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" class="border-b border-gray-400">
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.name }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.unit }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ product.quantity }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white text-right capitalize">
                                        {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white text-right capitalize">
                                        {{ totalPrice(product.price, product.quantity).toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    Nombre
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Unidad
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Cantidad
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Valor unitario
                                </th>
                                <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Valor total
                                </th>
                            </tfoot>
                        </table>
                        <Pagination class="my-6" :links="products.links" />
                        <div class="w-full flex justify-end items-center gap-5 mb-5">
                            <SuccessButton v-if="(canPay == true) && (order.payment_status == 'pending') && order.url" @click="openWebcheckout(order.code)" class="flex gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                    <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                </svg>
                                Pagar
                            </SuccessButton>
                            <InfoButton v-else-if="(order.payment_status == 'pending')  || (order.payment_status == 'canceled')" @click="generateNewURLWebcheckout()" class="flex gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path fill-rule="evenodd" d="M19.902 4.098a3.75 3.75 0 00-5.304 0l-4.5 4.5a3.75 3.75 0 001.035 6.037.75.75 0 01-.646 1.353 5.25 5.25 0 01-1.449-8.45l4.5-4.5a5.25 5.25 0 117.424 7.424l-1.757 1.757a.75.75 0 11-1.06-1.06l1.757-1.757a3.75 3.75 0 000-5.304zm-7.389 4.267a.75.75 0 011-.353 5.25 5.25 0 011.449 8.45l-4.5 4.5a5.25 5.25 0 11-7.424-7.424l1.757-1.757a.75.75 0 111.06 1.06l-1.757 1.757a3.75 3.75 0 105.304 5.304l4.5-4.5a3.75 3.75 0 00-1.035-6.037.75.75 0 01-.354-1z" clip-rule="evenodd" />
                                </svg>
                                Generar nuevo link de pago
                            </InfoButton>
                            <SuccessButton v-if="order.payment_status == 'waiting'" @click="openWebcheckout(order.code)" class="flex gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                    <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                </svg>
                                Ver estado de la transacción
                            </SuccessButton>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AlertError
            v-if="success === 'Payment error.'"
            title="Error de servicio externo"
            text="Intente el proceso de nuevo."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
