<script setup>
import { storeToRefs } from 'pinia';
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useCartStore } from '@/Store/Cart';
import { ref } from 'vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import InfoButton from '@/Components/Buttons/InfoButton.vue';
import InputError from '@/Components/InputError.vue';
import { watch } from 'vue';
import { reactive } from 'vue';

const props = defineProps({
    cancel: String,
    cartData: String,
    limitatedStock: String,
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
});

watch(order, value => {
    formSave.products = value;
});

const saveOrder = () => {
    formSave.post(route('order.store'));
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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">
                        <div v-if="errorAlert" class="bg-red-200 rounded-md w-full text-red-700 text-center py-3 mt-3">
                            Ha ocurrido un error al agregar este producto {{ errorInfo }}
                        </div>
                        <div v-if="success == 'Order rejected.'" @click="setProductsLimitated()" class="bg-red-200 rounded-md w-full text-red-700 text-center py-3 mt-3 cursor-pointer">
                            Alguno(s) de los productos sobrepasan las existencias disponibles, de click aquí para averiguarlos.
                        </div>
                        <div v-if="order[0]" class="w-full py-5">
                            <InputError class="mt-2" :message="formSave.errors.products" />
                            <table class="w-full m-5 rounded-lg">
                                <thead class="bg-gray-300 dark:bg-gray-700 rounded-t-lg">
                                    <th class="rounded-tl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-center">
                                        Articulo
                                    </th>
                                    <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Precio
                                    </th>
                                    <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Cantidad
                                    </th>
                                    <th class="border-r dark:border-r-0 py-3 text-black dark:text-white text-lg font-bold text-center">
                                        Precio total
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
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="w-6 h-6"
                                                >
                                                    <path fill-rule="evenodd" d="M3.75 12a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                                </svg>
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
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="w-6 h-6"
                                                >
                                                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="px-3 py-3 text-black dark:text-white text-right">
                                            {{ item.totalPrice.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                                        </td>
                                        <td class="px-3 py-3 text-black dark:text-white flex justify-center items-center gap-2">
                                            <button @click="showProduct(item.slug)" class="bg-blue-600 rounded-md text-white p-1">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                            <button @click="remove(item.id)" class="bg-red-600 rounded-md text-white p-1">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-300 dark:bg-gray-700 rounded-b-lg">
                                    <th colspan="3" class="rounded-bl-lg py-3 border-r dark:border-r-0 text-black dark:text-white text-lg font-bold text-right">
                                        Precio total
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
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" />
                                    </svg>
                                    Confirmar orden
                                </InfoButton>
                                <InfoButton v-else @click="updateOrder()" class="flex gap-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" />
                                    </svg>
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
    </AuthenticatedLayout>
</template>
