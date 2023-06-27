<script setup>
import { Head } from '@inertiajs/vue3';

import { useSignedRoleStore } from '@/Store/SignedRole';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DoughnutChart from '@/Components/Charts/DoughnutChart.vue';

const props = defineProps({
    categoriesData: Array,
    colors: Array,
    productsData: Array,
    userRole: String,
});

const useSignedRole = useSignedRoleStore();
const { assignRole} = useSignedRole;
assignRole(props.userRole);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Bienvenido al modo Administrador de MercadoTodo -->
                        <DoughnutChart
                            class="col-span-1"
                            :colors="colors"
                            :data="productsData"
                            :identificator="'TotalProducts'"
                            :label="'Total productos por categorías'"
                            :labels="categoriesData"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
