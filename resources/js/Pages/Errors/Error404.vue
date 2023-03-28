<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


</script>
<template>
    <Head title="Error 404 - Not found" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Error 404 - Not found
            </h2>
        </template>
    </AuthenticatedLayout>
</template>
