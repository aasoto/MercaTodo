<script setup>
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import TitlePage from '@/Components/TitlePage.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';


</script>
<template>
        <Head title="Elaborar reportes" />

        <AuthenticatedLayout>
            <template #header>
                <TitlePage>
                    Elaboración de reportes
                </TitlePage>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="m-5">
                            <div class="grid grid-cols-3 gap-5">
                                <Link :href="route('user.report.create')" class="col-span-1">
                                    <SuccessButton>
                                        Reportes de usuarios
                                    </SuccessButton>
                                </Link>
                                <Link :href="route('product.report.create')" class="col-span-1">
                                    <SuccessButton>
                                        Reportes de productos
                                    </SuccessButton>
                                </Link>
                                <Link :href="route('order.report.create')" class="col-span-1">
                                    <SuccessButton>
                                        Reportes de ordenes
                                    </SuccessButton>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
</template>
