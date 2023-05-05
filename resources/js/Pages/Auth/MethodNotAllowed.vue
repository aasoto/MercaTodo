<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

</script>

<template>
    <GuestLayout>
        <Head title="Error - 405" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            405 - El método solicitado es conocido por el servidor pero ha sido deshabilitado y no puede ser utilizado.
        </div>
        <Link :href="route('start')" class="flex justify-center items-center">
            <PrimaryButton>
                Volver la página principal
            </PrimaryButton>
        </Link>
    </GuestLayout>
</template>
