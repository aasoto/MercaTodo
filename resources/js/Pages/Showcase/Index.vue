<script setup>
import { ref, watch } from 'vue';

import { Head, Link, router, usePage } from '@inertiajs/vue3';

import { useSignedRoleStore } from '@/Store/SignedRole';
import { useCartStore } from '@/Store/Cart';

import { ShoppingCartIcon } from '@heroicons/vue/24/solid';

import AlertError from '@/Components/Alerts/AlertError.vue';
import AlertWarning from '@/Components/Alerts/AlertWarning.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownButtonFlexible from '@/Components/Buttons/DropdownButtonFlexible.vue';
import DropdownItem from '@/Components/Buttons/DropdownItem.vue';
import DropdownItemBox from '@/Components/Buttons/DropdownItemBox.vue';
import NotFoundMessage from "@/Components/NotFoundMessage.vue";
import Pagination from '@/Components/Pagination.vue';
import TitlePage from '@/Components/TitlePage.vue';

const props = defineProps({
    filters: Object,
    products: Object,
    products_categories: Object,
    success: String,
    userRole: String,
});

const useSignedRole = useSignedRoleStore();
const { assignRole } = useSignedRole;
assignRole(props.userRole);

const useCart = useCartStore();
const { currentUser, find } = useCart;

if (usePage().props.auth.user) {
    currentUser(usePage().props.auth.user.id);
}

const btnCategoryLabel = ref('Categorías');

const search = ref(props.filters.search);
const category = ref(props.filters.category);
const minPrice = ref(props.filters.minPrice);
const maxPrice = ref(props.filters.maxPrice);

watch(search, value => {
    getResults();
});

watch(category, value => {
    getResults();
});

const setCategory = (productCategory) => {
    if (productCategory === 'Categorías') {
        category.value = '';
    } else {
        category.value = productCategory;
    }
    btnCategoryLabel.value = productCategory;
}

watch(minPrice, value => {
    getResults();
});

watch(maxPrice, value => {
    getResults();
});

const getResults = () => {
    if ((minPrice.value > 0) && (maxPrice.value >= minPrice.value)) {
        router.get('/showcase', {
            search: search.value,
            category: category.value,
            minPrice: minPrice.value,
            maxPrice: maxPrice.value
        }, {
            preserveState: true,
            replace: true,
        });
    } else {
        if ((!minPrice.value) && (!maxPrice.value))
        {
            router.get('/showcase', {
                search: search.value,
                category: category.value,
            }, {
                preserveState: true,
                replace: true,
            });
        }
    }
}

</script>
<template>
    <Head v-if="$page.props.auth.user" title="Vitrina de productos" />
    <Head v-else title="MercaTodo"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <div class="col-span-1 flex justify-center items-center">
                    <TitlePage>
                        Vitrina de productos
                    </TitlePage>
                </div>
                <div class="col-span-2">
                    <input
                        v-model="search"
                        type="text"
                        class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                        placeholder="Buscar..."
                    >
                </div>
                <DropdownButtonFlexible :btn-label="btnCategoryLabel" class="col-span-1">
                    <DropdownItemBox>
                        <DropdownItem @click="setCategory('Categorías')">
                            Ninguna
                        </DropdownItem>
                    </DropdownItemBox>
                    <DropdownItemBox>
                        <DropdownItem
                            v-for="product_category in products_categories"
                            @click="setCategory(product_category.name)"
                            :key="product_category.id"
                        >
                            {{ product_category.name }}
                        </DropdownItem>
                    </DropdownItemBox>
                </DropdownButtonFlexible>

                <div class="col-span-1 flex justify-center items-center gap-2">
                    <input
                        v-model="minPrice"
                        type="number"
                        placeholder="Valor min"
                        class="w-28 px-2 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                    >
                    <input
                        v-model="maxPrice"
                        type="number"
                        placeholder="Valor max"
                        class="w-28 px-2 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                    >
                </div>
            </div>
        </template>
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div
                    v-for="product in products.data"
                    class="bg-white dark:bg-gray-800 rounded-md p-4 shadow-md hover:shadow-lg scale-100 hover:scale-105 transition duration-200 flex flex-col gap-4"
                >
                    <Link :href="route('showcase.show', product.slug)" class="relative">
                        <img
                            class="w-full h-56 rounded object-cover object-center cursor-pointer"
                            :src="`../storage/images/products/${product.picture_1}`"
                            alt="product_image_1"
                        >
                        <div class="absolute top-5 right-5">
                            <div
                                v-if="find(product.id)"
                                class="relative h-12 w-12 bg-black/40 flex items-center justify-center rounded-full"
                            >
                                <ShoppingCartIcon class="w-6 h-6 text-white"/>
                            </div>
                            <div v-if="product.stock == 0" class="relative h-12 w-max px-2 bg-black/40 flex items-center justify-center rounded-md text-white">
                                AGOTADO
                            </div>
                        </div>
                        <h2 class="text-lg truncate font-medium hover:font-bold no-underline hover:underline cursor-pointer capitalize">
                            {{ product.name }}
                        </h2>
                        <div class="px-3 py-1 border border-gray-600 dark:border-gray-500 rounded-full w-max shadow-none hover:shadow scale-100 hover:scale-105 transition duration-200 cursor-pointer">
                            <h5 class="text-gray-600 dark:text-gray-500 text-sm font-light capitalize">
                                {{ product.category }}
                            </h5>
                        </div>
                        <div>
                            <h1 class="text-2xl font-extrabold text-right">
                                {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                            </h1>
                            <h5 class="text-gray-400 italic text-sm text-right lowercase">
                                cada {{ product.unit }}
                            </h5>
                        </div>
                    </Link>
                </div>
                <NotFoundMessage
                    v-if="products.data.length <= 0"
                    class="col-span-1 md:col-span-2 lg:col-span-3 xl:col-span-4"
                />
            </div>
            <Pagination class="my-6" :links="products.links" />
        </div>
        <AlertError
            v-if="success === 'Payment canceled.'"
            title="¡Proceso de pago cancelado!"
            text="Si desea retormar el proceso de nuevo, busque en la pestaña de ordenes el pedido que canceló, este debe aparecer con el estado de cancelado. En caso de que tenga varios pedidos pendentes puede buscarlos por la fecha de compra o simplemente cliqueando en el botón de detalles de la orden para ver los articulos que hay en ella, recuerde que el link de compra tiene un duración de 24 horas, si el link está expirado deberá generar un nuevo."
            :close="false"
            :btn-close="true"
        />
        <AlertWarning
            v-if="success === 'Payment unauthorized.'"
            title="Error de autorización"
            text="Espere a que los administradores de esta plataforma solucionen."
            :close="false"
            :btn-close="true"
        />
        <AlertError
            v-if="success === 'Payment error.'"
            title="Error del servidor"
            text="Intente el proceso de nuevo o espere mas tarde."
            :close="false"
            :btn-close="true"
        />
        <AlertError
            v-if="success === 'Payment undefined error.'"
            title="Error desconocido del servidor"
            text="Intente el proceso de nuevo o espere mas tarde."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
