<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

import { storeToRefs } from 'pinia';
import { useSignedRoleStore } from '@/Store/SignedRole';
import { useCartStore } from "@/Store/Cart";

import { TrashIcon } from '@heroicons/vue/24/outline';
import { MinusIcon, PlusIcon, ShoppingCartIcon } from '@heroicons/vue/24/solid';

// Props
const props = defineProps({
    product: Object,
});

//define signed role
const useSignedRole = useSignedRoleStore();
const { role } = storeToRefs(useSignedRole);

//quantity of products
const quantity = ref(1);
const maxStock = ref(false);

const decrement = () => {
    if (quantity.value > 1) {
        quantity.value = quantity.value - 1;
    }
}

const increment = () => {
    if (quantity.value < props.product.stock) {
        quantity.value = quantity.value + 1;
    } else {
        maxStock.value = true;
        setTimeout(() => {
            maxStock.value = false;
        }, 8000);
    }
}

//iterate pictures
const mainPicture = ref(props.product.picture_1);
const alternativePicture1 = ref(props.product.picture_2);
const alternativePicture2 = ref(props.product.picture_3);

const watchOtherPicture = (image) => {
    if (alternativePicture1.value === image) {
        alternativePicture1.value = mainPicture.value;
        mainPicture.value = image;
    }
    if (alternativePicture2.value === image) {
        alternativePicture2.value = mainPicture.value;
        mainPicture.value = image;
    }
}

// Add to the cart
const useCart = useCartStore();
const success = ref(false);
const updated = ref(false);
const errorAlert = ref(false);
const errorInfo = ref('');

const { add, find, update, remove } = useCart;

let found = find(props.product.id);

if (found?.quantity) {
    quantity.value = found.quantity;
}

const actionAdd = () => {
    found = find(props.product.id);
    found ? updateCart() : addToCart();
}

const addToCart = () => {
    try {
        add(props.product.id, props.product.name, props.product.slug, props.product.price, quantity.value);
        errorAlert.value = false;
        success.value = true;
        setTimeout(() => {
            success.value = false;
        }, 3000);
    } catch (error) {
        errorAlert.value = true;
        errorInfo.value = error;
    }
}

const updateCart = () => {
    try {
        update(props.product.id, quantity.value);
        errorAlert.value = false;
        updated.value = true;
        setTimeout(() => {
            updated.value = false;
        }, 3000);
    } catch (error) {
        errorAlert.value = true;
        errorInfo.value = error;
    }
}

const removeFromCart = () => {
    remove(props.product.id);
    found = find(props.product.id);
}

</script>
<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">

                <h2 class="font-semibold mt-10 text-4xl text-gray-800 dark:text-gray-200 leading-tight capitalize">
                    {{ product.name }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
                    <div class="col-span-1 md:col-span-2 grid grid-cols-7 gap-4">
                        <div class="col-span-5">
                            <img
                                class="w-full"
                                :src="`../storage/images/products/${mainPicture}`"
                                alt="product_photo_1"
                            >
                        </div>
                        <div class="col-span-2 flex flex-col gap-4">
                            <img
                                v-show="alternativePicture1"
                                @click="watchOtherPicture(alternativePicture1)"
                                :src="`../storage/images/products/${alternativePicture1}`"
                                alt="product_photo_2"
                            >
                            <img
                                v-show="alternativePicture2"
                                @click="watchOtherPicture(alternativePicture2)"
                                :src="`../storage/images/products/${alternativePicture2}`"
                                alt="product_photo_3"
                            >
                        </div>
                    </div>
                    <div class="col-span-1 flex flex-col gap-4">
                        <div class="px-6 py-2 border border-gray-600 dark:border-gray-500 rounded-full w-max mx-auto shadow-none hover:shadow scale-100 hover:scale-105 transition duration-200 cursor-pointer">
                            <h5 class="text-gray-600 dark:text-gray-500 text-xl font-light capitalize">
                                {{ product.category }}
                            </h5>
                        </div>
                        <div class="text-right text-black dark:text-white">
                            <h3 class="text-4xl font-bold">
                                {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                            </h3>
                            <h6 class="font-extralight italic">
                                cada {{ product.unit }}
                            </h6>
                        </div>
                        <div
                            class="text-lg text-black dark:text-white"
                            v-html="product.description"
                        ></div>
                        <div class="rounded-lg border-2 border-gray-500 w-full px-10 py-4 text-center text-gray-500 text-xl font-semibold">
                            Quedan
                            <span v-if="product.stock <= 5" class="font-bold text-red-500">
                                {{' ' + product.stock + ' ' }}
                            </span>
                            <span v-else class="font-bold text-gray-700">
                                {{ ' ' + product.stock + ' ' }}
                            </span>
                            {{ product.unit + ' ' }} en stock.
                        </div>
                        <div v-if="role == 'client' && product.stock > 0" class="flex justify-center items-center gap-5">
                            <div class="flex flex-col justify-center items-center">
                                <label for="btnIncrement" class="font-bold text-3xl">
                                    Cantidad
                                </label>
                                <div class="flex">
                                    <button
                                        @click="decrement()"
                                        class="rounded-md px-3 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-transparent text-gray-900 dark:text-white font-bold border-none dark:border border-transparent dark:border-white scale-100 hover:scale-105 transition duration-200"
                                    >
                                        <MinusIcon class="w-6 h-6"/>
                                    </button>
                                    <h3 class="font-bold text-3xl mx-3">
                                        {{ quantity }}
                                    </h3>
                                    <button
                                        @click="increment()"
                                        class="rounded-md px-3 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-transparent text-gray-900 dark:text-white font-bold border-none dark:border border-transparent dark:border-white scale-100 hover:scale-105 transition duration-200"
                                    >
                                        <PlusIcon class="w-6 h-6"/>
                                    </button>
                                </div>
                            </div>
                            <button
                                @click="actionAdd()"
                                class="flex justify-center items-center gap-2 scale-100 hover:scale-105 px-5 py-3 rounded-md transition duration-200"
                                :class="find(product.id) ? 'bg-yellow-300 hover:bg-yellow-400 text-black' : 'bg-red-500 hover:bg-red-600 text-white'"
                            >
                                <span>
                                    <template v-if="find(product.id)">
                                        Actualizar cantidad
                                    </template>
                                    <template v-else>
                                        Añadir al carrito
                                    </template>
                                </span>
                                <ShoppingCartIcon class="w-6 h-6"/>
                            </button>
                            <button v-if="find(product.id)" @click="removeFromCart()" class="rounded-md px-5 py-3 bg-gray-200 text-gray-800 scale-100 hover:scale-105 transition duration-200">
                                <TrashIcon class="w-6 h-6"/>
                            </button>
                        </div>
                        <div v-if="success" class="bg-green-200 rounded-md w-full text-green-700 text-center py-3">
                            Agregado al carrito de compras
                        </div>
                        <div v-if="errorAlert" class="bg-red-200 rounded-md w-full text-red-700 text-center py-3">
                            Ha ocurrido un error al agregar este producto {{ errorInfo }}
                        </div>
                        <div v-if="updated" class="bg-yellow-200 rounded-md w-full text-yellow-700 text-center py-3">
                            Actualizado correctamente
                        </div>
                        <div v-if="product.stock == 0" class="bg-yellow-200 rounded-md w-full text-yellow-700 text-center py-3">
                            Producto agotado
                        </div>
                        <div v-if="maxStock" class="bg-blue-200 rounded-md w-full text-blue-700 text-center py-3">
                            Cantidad máxima de existencias (puede que aún no todas estén disponibles)
                        </div>
                        <Link v-if="!$page.props.auth.user" :href="route('login')" class="bg-red-200 rounded-md w-full text-red-700 text-center py-3 cursor-pointer">
                            Para comprar este producto debe iniciar sesión, click aquí
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
