<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    cities: Array,
    mustVerifyEmail: {
        type: Boolean,
    },
    role: String,
    states: Array,
    status: {
        type: String,
    },
    typeDocuments: Object,
});

</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout :userRole="role">
        <template #header v-if="role == 'admin'">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Perfil - Administrador</h2>
        </template>
        <template #header v-if="role == 'client'">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Perfil - Cliente</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :cities="cities"
                        :must-verify-email="mustVerifyEmail"
                        :states="states"
                        :status="status"
                        :typeDocuments="typeDocuments"
                        class="w-full"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
