<script setup>
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    orders: Object,
    success: String,
});

const form = useForm({
    id: '',
});

const showOrderDetail = (id) => {
    form.id = id;
    form.get(route('order.show', form.id));
}

const openWebcheckout = (url) => {
    window.location.replace(url);
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

const generateNewURLWebcheckout = (id) => {
    router.post(route('payment.update', id),{
        _method: 'patch',
        id: id,
    });
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
                        <table v-if="orders" class="w-full m-5 rounded-lg">
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
                                            class="rounded-md px-4 py-2 bg-red-200 text-center font-bold"
                                        >
                                            CANCELADO
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'paid'"
                                            class="rounded-md px-4 py-2 bg-green-200 text-center font-bold"
                                        >
                                            PAGADO
                                        </div>
                                        <div
                                            v-if="order.payment_status == 'waiting'"
                                            class="rounded-md px-4 py-2 bg-purple-300 text-center font-bold"
                                        >
                                            PAGO POR CONFIRMAR
                                        </div>
                                        <div
                                            v-if="order.url && (order.payment_status == 'pending')"
                                            class="rounded-md px-4 py-2 bg-yellow-200 text-center font-bold"
                                        >
                                            PENDIENTE
                                        </div>
                                        <div
                                            v-if="!order.url"
                                            class="rounded-md px-4 py-2 bg-blue-200 text-center font-bold"
                                        >
                                            SIN LINK DE PAGO
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white text-right capitalize">
                                        {{ order.purchase_total.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </td>
                                    <td class="px-3 py-3 text-black dark:text-white capitalize flex justify-center items-center gap-3">
                                        <button @click="showOrderDetail(order.id)" class="bg-blue-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>
                                                Detalles
                                            </span>
                                        </button>
                                        <button v-if="(order.payment_status == 'pending') && expirationDate(order.updated_at) && order.url" @click="openWebcheckout(order.url)" class="bg-green-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                                <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                            </svg>
                                            Pagar orden
                                        </button>
                                        <button v-else-if="(order.payment_status == 'pending') || (order.payment_status == 'canceled')" @click="generateNewURLWebcheckout(order.id)" class="bg-cyan-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M19.902 4.098a3.75 3.75 0 00-5.304 0l-4.5 4.5a3.75 3.75 0 001.035 6.037.75.75 0 01-.646 1.353 5.25 5.25 0 01-1.449-8.45l4.5-4.5a5.25 5.25 0 117.424 7.424l-1.757 1.757a.75.75 0 11-1.06-1.06l1.757-1.757a3.75 3.75 0 000-5.304zm-7.389 4.267a.75.75 0 011-.353 5.25 5.25 0 011.449 8.45l-4.5 4.5a5.25 5.25 0 11-7.424-7.424l1.757-1.757a.75.75 0 111.06 1.06l-1.757 1.757a3.75 3.75 0 105.304 5.304l4.5-4.5a3.75 3.75 0 00-1.035-6.037.75.75 0 01-.354-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span v-if="!order.url">
                                                Generar link de pago
                                            </span>
                                            <span v-else>
                                                Generar nuevo link de pago
                                            </span>
                                        </button>
                                        <button v-if="(order.payment_status == 'waiting') && order.url" @click="openWebcheckout(order.url)" class="bg-green-600 rounded-md text-white px-3 py-1 flex justify-center items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                                <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                            </svg>
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
    </AuthenticatedLayout>
</template>
