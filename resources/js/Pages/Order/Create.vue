<script setup>
import { reactive, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

import { storeToRefs } from 'pinia';
import { useCartStore } from '@/Store/Cart';

import { CheckIcon, EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ChevronDownIcon, MinusIcon, PlusIcon } from '@heroicons/vue/24/solid';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import InfoButton from '@/Components/Buttons/InfoButton.vue';
import InputError from '@/Components/InputError.vue';
import AlertError from '@/Components/Alerts/AlertError.vue';

const props = defineProps({
    cancel: String,
    cartData: String,
    limitatedStock: String,
    paymentMethods: Object,
    orderId: String,
    success: String,
});

const useCart = useCartStore();
const { order, totalPriceOrder } = storeToRefs(useCart);
const { restore, remove, update } = useCart;

if (props.cancel == 'Restore order.') {
    const cart = JSON.parse(props.cartData);
    restore(cart.products);
}

const productsLimitated = ref();

const setProductsLimitated = () => {
    productsLimitated.value = JSON.parse(props.limitatedStock);
}

const form = useForm({
    slug: '',
});

const showProduct = (slug) => {
    form.slug = slug;
    form.get(route('showcase.show', form.slug));
}
const formSave = useForm({
    products: order.value,
    payment_method: '',
});

watch(order, value => {
    formSave.products = value;
});

const btnPaymentMethodLabel = ref('Metodo de pago');
const showAlertPaymentMethod = ref(false);

const setPaymentMethod = (code, name) => {
    if (code === 'NONE') {
        formSave.payment_method = '';
    } else {
        formSave.payment_method = code;
    }
    btnPaymentMethodLabel.value = name;
}

const saveOrder = () => {
    if (formSave.payment_method == '') {
        showAlertPaymentMethod.value = true;
    } else {
        formSave.post(route('order.store'));
    }
}

const formUpdate = reactive({
    id: props.orderId,
    products: order.value,
});

watch(order, value => {
    formUpdate.products = value;
});

const updateOrder = () => {
    router.post(route('payment.update', formUpdate.id),{
        _method: 'patch',
        id: formUpdate.id,
        products: formUpdate.products,
    });
}

const errorAlert = ref(false);
const errorInfo = ref('');

const decrement = (productId, productQuantity) => {
    try {
        if (productQuantity > 1) {
            productQuantity--;
            update(productId, productQuantity);
            errorAlert.value = false;
        }
    } catch (error) {
        errorAlert.value = true;
        errorInfo.value = error;
    }
}

const increment = (productId, productQuantity) => {
    try {
        productQuantity++;
        update(productId, productQuantity);
        errorAlert.value = false;
    } catch (error) {
        errorAlert.value = true;
        errorInfo.value = error;
    }
}

</script>
<template>
    <Head title="Vitrina de productos" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Mi orden
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <div v-if="errorAlert" class="bg-red-200 rounded-md w-full text-red-700 text-center py-3 mt-3">
                            Ha ocurrido un error al agregar este producto {{ errorInfo }}
                        </div>
                        <div v-if="success == 'Order rejected.'" @click="setProductsLimitated()" class="bg-red-200 rounded-md w-full text-red-700 text-center py-3 mt-3 cursor-pointer">
                            Alguno(s) de los productos sobrepasan las existencias disponibles, de click aquí para averiguarlos.
                        </div>
                        <div v-if="order[0]" class="w-full py-5">
                            <InputError class="mt-2" :message="formSave.errors.products" />
                            <div class="flex justify-end items-center gap-5">
                                <div class="relative group">
                                    <button
                                        id="dropdownDefaultButton"
                                        class="w-full text-black dark:text-white bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded group-hover:rounded-t group-hover:rounded-b-none px-5 py-3 text-center inline-flex justify-between items-center shadow-none group-hover:shadow transition duration-200"
                                        type="button"
                                    >
                                        <span v-html="btnPaymentMethodLabel" class="capitalize"></span>
                                        <ChevronDownIcon class="w-4 h-4 ml-2"/>
                                    </button>
                                    <!-- Dropdown menu -->
                                    <div id="dropdown" class="absolute z-10 hidden group-hover:block bg-white divide-y divide-gray-100 rounded-b shadow w-full dark:bg-gray-700 transition duration-200">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                            <li>
                                                <span @click="setPaymentMethod('NONE', 'Metodo de pago')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                    Ninguno
                                                </span>
                                            </li>
                                        </ul>
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                            <li v-for="paymentMethod in paymentMethods">
                                                <span @click="setPaymentMethod(paymentMethod.code, paymentMethod.name)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white capitalize cursor-pointer">
                                                    {{ paymentMethod.name }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <table class="w-full m-5 rounded-lg">
                                <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                    <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                        Articulo
                                    </th>
                                    <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Valor unitario
                                    </th>
                                    <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Cantidad
                                    </th>
                                    <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Valor total
                                    </th>
                                    <th class="rounded-tr-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Acciones
                                    </th>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-400" v-for="item in order">
                                        <td class="px-3 py-3 text-black dark:text-white">
                                            <div class="capitalize">
                                                {{ item.name }}
                                            </div>
                                            <template v-for="limitated in productsLimitated">
                                                <div v-if="limitated.id == item.id" class="text-sm text-red-600">
                                                    Este producto tiene unidades limitadas escoga un número igual o menor al inferior de color rojo dado en la columna de cantidad
                                                </div>
                                            </template>
                                        </td>
                                        <td class="px-3 py-3 text-black dark:text-white text-right">
                                            {{ item.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                        </td>
                                        <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center gap-3">
                                            <button @click="decrement(item.id, item.quantity)" class="rounded-md p-1 bg-gray-300 hover:bg-gray-400 dark:bg-transparent text-gray-900 dark:text-white font-bold border-none dark:border border-transparent dark:border-white scale-100 hover:scale-105 transition duration-200">
                                                <MinusIcon class="w-6 h-6"/>
                                            </button>
                                            <span class="font-bold text-lg text-gray-900 dark:text-white">
                                                <div>
                                                    {{ item.quantity }}
                                                </div>
                                                <template v-for="limitated in productsLimitated">
                                                    <div v-if="limitated.id == item.id" class="text-sm text-red-600">
                                                        {{ limitated.stock }}
                                                    </div>
                                                </template>
                                            </span>
                                            <button @click="increment(item.id, item.quantity)" class="rounded-md p-1 bg-gray-300 hover:bg-gray-400 dark:bg-transparent text-gray-900 dark:text-white font-bold border-none dark:border border-transparent dark:border-white scale-100 hover:scale-105 transition duration-200">
                                                <PlusIcon class="w-6 h-6"/>
                                            </button>
                                        </td>
                                        <td class="px-3 py-3 text-black dark:text-white text-right">
                                            {{ item.totalPrice.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                        </td>
                                        <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center gap-2">
                                            <button @click="showProduct(item.slug)" class="bg-blue-600 rounded-md text-white p-1">
                                                <EyeIcon class="w-4 h-4"/>
                                            </button>
                                            <button @click="remove(item.id)" class="bg-red-600 rounded-md text-white p-1">
                                                <TrashIcon class="w-4 h-4"/>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                    <th colspan="3" class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-right">
                                        Total orden
                                    </th>
                                    <th class="border-r dark:border-r-0 p-3 text-black dark:text-white text-lg font-bold text-right">
                                        {{ totalPriceOrder.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                    </th>
                                    <th class="rounded-br-lg py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Acciones
                                    </th>
                                </tfoot>
                            </table>
                            <div class="flex justify-end items-center gap-5">
                                <InfoButton v-if="!cancel" @click="saveOrder()" class="flex gap-4">
                                    <CheckIcon class="w-6 h-6"/>
                                    Confirmar orden
                                </InfoButton>
                                <InfoButton v-else @click="updateOrder()" class="flex gap-4">
                                    <CheckIcon class="w-6 h-6"/>
                                    Actualizar orden
                                </InfoButton>
                            </div>
                        </div>
                        <div v-else class="w-full my-5 px-10 py-5 border border-blue-600 bg-blue-300 rounded-lg text-blue-700 text-center">
                            No hay elementos en el carrito de compra, vaya a la vitrina y busque los articulos que desea ordenar
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
