<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <GuestLayout>
        <Head title="Error - 405" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            405 - El método solicitado es conocido por el servidor pero ha sido deshabilitado y no puede ser utilizado.
        </div>
    </GuestLayout>
</template>
