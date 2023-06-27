<script setup>
import { Head } from '@inertiajs/vue3';

import { useSignedRoleStore } from '@/Store/SignedRole';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import BasicCard from '@/Components/Cards/BasicCard.vue';
import DoughnutChart from '@/Components/Charts/DoughnutChart.vue';
import TitlePage from '@/Components/TitlePage.vue';
import LineChart from '@/Components/Charts/LineChart.vue';

const props = defineProps({
    ordersByDay: Object,
    productsByCategory: Object,
    productsStatusByStock: Object,
    productsByAvailability: Object,
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
            <TitlePage>
                Dashboard
            </TitlePage>
        </template>

        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <BasicCard title="Total productos por categorías" class="col-span-1">
                    <DoughnutChart
                        class="mx-24"
                        :colors="productsByCategory.colors"
                        :data="productsByCategory.data"
                        :identificator="'totalProducts'"
                        :label="'Total productos por categorías'"
                        :labels="productsByCategory.labels"
                    />
                </BasicCard>

                <BasicCard title="Estado de los productos según su stock" class="col-span-1">
                    <DoughnutChart
                        class="mx-24"
                        :colors="productsStatusByStock.colors"
                        :data="productsStatusByStock.data"
                        :identificator="'productsStatusByStock'"
                        :label="'Estado de los productos según su stock'"
                        :labels="productsStatusByStock.labels"
                    />
                </BasicCard>

                <BasicCard title="Productos habilitados e inhabilitados" class="col-span-1">
                    <DoughnutChart
                        class="mx-24"
                        :colors="productsByAvailability.colors"
                        :data="productsByAvailability.data"
                        :identificator="'productsByAvailability'"
                        :label="'Productos habilitados e inhabilitados'"
                        :labels="productsByAvailability.labels"
                    />
                </BasicCard>

                <BasicCard title="Número de ordenes hechas por día">
                    <LineChart
                        :color="ordersByDay.color"
                        :data="ordersByDay.data"
                        identificator="ordersByDay"
                        label="Número de ordenes hechas por día"
                        :labels="ordersByDay.labels"
                    />
                </BasicCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
