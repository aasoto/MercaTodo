<script setup>
import ProductDescription from '@/Components/ProductDescription.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
});
console.log(props.product);

</script>
<template>
    <Head :title="`MercaTodo - ${product.name}`" />
    <AuthenticatedLayout>
        <ProductDescription :product="product" />
    </AuthenticatedLayout>
</template>
