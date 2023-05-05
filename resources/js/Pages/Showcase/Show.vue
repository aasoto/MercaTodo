<script setup>
import ProductDescription from '@/Components/ProductDescription.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    product: Object,
});

</script>
<template>
    <Head :title="`MercaTodo - ${product.name}`" />
    <AuthenticatedLayout>
        <ProductDescription :product="product" />
    </AuthenticatedLayout>
</template>
