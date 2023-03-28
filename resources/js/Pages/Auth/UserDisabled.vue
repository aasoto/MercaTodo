<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <GuestLayout>
        <Head title="Usuario inhabilitado" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Este usuario se encuentra inhabilitado, comuniquese con los administradores.
        </div>

        <Link
            :href="route('logout')"
            method="post"
        >
            <div class="mt-4 flex items-center justify-center">
                <PrimaryButton>
                    Cerrar sesión
                </PrimaryButton>
            </div>
        </Link>
    </GuestLayout>
</template>
