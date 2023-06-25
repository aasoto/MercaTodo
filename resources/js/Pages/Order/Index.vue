<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

import { EyeIcon, LinkIcon } from '@heroicons/vue/24/outline';
import { ChevronDownIcon, CreditCardIcon } from '@heroicons/vue/24/solid';

import AlertError from '@/Components/Alerts/AlertError.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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
</script>
<template>
    <Head title="Mis ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Mis ordenes
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <table v-if="orders.data.length != 0" class="w-full m-5 rounded-lg">
                            <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    ID
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Fecha de la compra
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Fecha del pago
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Estado de pago
                                </th>
                                <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Total de la compra
                                </th>
                                <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="order in orders.data" class="border-b border-gray-400">
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ order.id }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ dateGMT_5(order.purchase_date) }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        {{ order.payment_date ? dateGMT_5(order.payment_date) : '' }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize">
                                        <div
                                            v-if="order.payment_status == 'canceled'"
                                            class="rounded-md px-2 py-2 bg-red-200 text-center font-bold"
                                        >
                                            CANCELADO
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'paid'"
                                            class="rounded-md px-2 py-2 bg-green-200 text-center font-bold"
                                        >
                                            PAGADO
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'waiting'"
                                            class="rounded-md px-2 py-2 bg-purple-300 text-center font-bold"
                                        >
                                            PAGO POR CONFIRMAR
                                        </div>
                                        <div
                                            v-if="order.url && (order.payment_status == 'pending')"
                                            class="rounded-md px-2 py-2 bg-yellow-200 text-center font-bold"
                                        >
                                            PENDIENTE
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'verify_bank'"
                                            class="rounded-md px-2 py-2 bg-orange-300 text-center font-bold"
                                        >
                                            VERIFICAR CON SU BANCO
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'approved_partial'"
                                            class="rounded-md px-2 py-2 bg-amber-300 text-center font-bold"
                                        >
                                            PAGO INCOMPLETO
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'partial_expired'"
                                            class="rounded-md px-2 py-2 bg-zinc-300 text-center font-bold"
                                        >
                                            PAGO EXPIRADO
                                        </div>
                                        <div
                                            v-if="!order.url"
                                            class="rounded-md px-2 py-2 bg-blue-200 text-center font-bold"
                                        >
                                            SIN LINK DE PAGO
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white text-right capitalize">
                                        {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize flex justify-center items-center gap-3">
                                        <button @click="showOrderDetail(order.id)" class="bg-blue-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <EyeIcon class="w-4 h-4"/>
                                            <span>
                                                Detalles
                                            </span>
                                        </button>
                                        <div v-if="(order.payment_status == 'pending' && !order.url) || order.payment_status == 'canceled'" class="relative group">
                                            <button
                                                id="dropdownDefaultButton"
                                                class="w-full text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-md group-hover:rounded-t group-hover:rounded-b-none px-4 py-1 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                                                type="button"
                                            >
                                                <span v-html="regenerateLinkId == order.id ? btnPaymentMethodLabel : 'Metodo de pago'" class="capitalize"></span>
                                                <ChevronDownIcon class="w-4 h-4 ml-2"/>
                                            </button>
                                            <!-- Dropdown menu -->
                                            <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full dark:bg-gray-700 transition duration-200">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                                    <li>
                                                        <span @click="setPaymentMethod('NONE', 'Metodo de pago', order.id)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                            Ninguno
                                                        </span>
                                                    </li>
                                                </ul>
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                                    <li v-for="paymentMethod in paymentMethods">
                                                        <span @click="setPaymentMethod(paymentMethod.code, paymentMethod.name, order.id)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                            {{ paymentMethod.name }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <button v-if="(order.payment_status == 'pending' || order.payment_status == 'approved_partial') && expirationDate(order.updated_at) && order.url" @click="openWebcheckout(order.code)" class="bg-green-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <CreditCardIcon class="w-4 h-4"/>
                                            <span v-if="order.payment_status == 'pending'">
                                                Pagar orden
                                            </span>
                                            <span v-else>
                                                Completar pago
                                            </span>
                                        </button>
                                        <button v-else-if="(order.payment_status == 'pending' || order.payment_status == 'canceled') && paymentMethod && (regenerateLinkId == order.id)" @click="generateNewURLWebcheckout(order.id)" class="bg-cyan-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <LinkIcon class="w-6 h-6"/>
                                            <span v-if="!order.url">
                                                Generar link de pago
                                            </span>
                                            <span v-else>
                                                Generar nuevo link de pago
                                            </span>
                                        </button>
                                        <button v-if="(order.payment_status == 'waiting') && order.url" @click="openWebcheckout(order.code)" class="bg-green-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <CreditCardIcon class="w-4 h-4"/>
                                            Ver estado transacción
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                <th class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                    ID
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Fecha de la compra
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Fecha del pago
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Estado de pago
                                </th>
                                <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-center">
                                    Total de la compra
                                </th>
                                <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                    Acciones
                                </th>
                            </tfoot>
                        </table>
                        <div v-else class="w-full my-5 px-10 py-5 border border-blue-600 bg-blue-300 rounded-lg text-blue-700 text-center">
                            Listado de ordenes vacio
                        </div>
                        <Pagination class="my-6" :links="orders.links" />
                    </div>
                </div>
            </div>
        </div>
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
