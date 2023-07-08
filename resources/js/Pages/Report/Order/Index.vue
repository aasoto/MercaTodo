<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TitlePage from '@/Components/TitlePage.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';

const props = defineProps({
    success: String,
});
</script>
<template>
    <Head title="Reporte de ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Reporte de ordenes
                </TitlePage>
            </div>
        </template>
        <BasicBodyPage>
            <Link :href="route('order.report.export')">
                <SuccessButton>
                    Generar reporte de ordenes
                </SuccessButton>
            </Link>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Orders report generated.'"
            title="¡Bien Hecho!"
            text="La exportación de productos fue encolada."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
