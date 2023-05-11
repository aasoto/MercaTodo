<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link } from '@inertiajs/vue3';

</script>
<template>
    <GuestLayout>
        <Head title="Usuario inhabilitado" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Este usuario no tiene un rol asignado o cuenta con un tipo de rol no soportado por el sistema, comuniquese con los administradores.
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
