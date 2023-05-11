<script setup>
import InfoError from '@/Components/Infos/InfoError.vue';
import ProductDescription from '@/Components/ProductDescription.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    product: Object,
});

</script>
<template>
    <Head :title="`MercaTodo - ${product?.name}`" />
    <AuthenticatedLayout>
        <template v-if="product?.availability">
            <ProductDescription v-if="product" :product="product" />
            <InfoError v-else class="mt-10 mx-10 w-auto">
                Producto no encontrado
            </InfoError>
        </template>
        <template v-else>
            <InfoError class="mt-10 mx-10 w-auto">
                Este producto ha sido inhabilitado por los administradores del sistema.
            </InfoError>
        </template>
    </AuthenticatedLayout>
</template>
