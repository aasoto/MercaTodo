<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';

    defineProps({
        typeDocuments: Object,
    })
</script>
<template>
    <Head title="Listado de usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Listado de usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-10">
                    <div class="flex flex-col justify-center items-center">

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
