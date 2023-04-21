<script setup>
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useSignedRoleStore } from '@/Store/SignedRole';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    products: Object,
    userRole: String,
});

const useSignedRole = useSignedRoleStore();
const { assignRole} = useSignedRole;
assignRole(props.userRole);

</script>
<template>
    <Head title="Vitrina de productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Vitrina de productos
            </h2>
        </template>
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="grid grid-cols-4 gap-4">
                <div
                    v-for="product in products.data"
                    class="bg-white rounded-md p-4 shadow-md hover:shadow-lg scale-100 hover:scale-105 transition duration-200 flex flex-col gap-4"
                >
                    <Link :href="route('product.show', product.slug)">
                        <img
                            class="w-full h-1/2 rounded object-cover object-center cursor-pointer"
                            :src="`../images/products/${product.picture_1}`"
                            alt="product_image_1"
                        >
                        <h2 class="text-lg truncate font-medium hover:font-bold no-underline hover:underline cursor-pointer capitalize">
                            {{ product.name }}
                        </h2>
                        <div class="px-3 py-1 border border-gray-600 rounded-full w-max shadow-none hover:shadow scale-100 hover:scale-105 transition duration-200 cursor-pointer">
                            <h5 class="text-gray-600 text-sm font-light capitalize">
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
            </div>
            <Pagination class="my-6" :links="products.links" />
        </div>
    </AuthenticatedLayout>
</template>
