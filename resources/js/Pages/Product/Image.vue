<script setup>
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/Inputs/InputFile.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TitlePage from '@/Components/TitlePage.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    fileName: String,
});

const form = useForm({
    image_file: '',
});
</script>
<template>
    <Head title="Agregar producto" />

    <AuthenticatedLayout>
        <template #header>
            <TitlePage>
                Adjuntar directamente imagenes de productos
            </TitlePage>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-5">
                        <form @submit.prevent="form.post(route('product.image.store'))" class="mt-6 space-y-6">
                            <div class="grid grid-cols-2 gap-5 my-5">
                                <div class="col-span-1">
                                    <InputLabel for="picture_1">
                                        Archivo<span class="text-red-600"> *</span>
                                    </InputLabel>
                                    <InputFile
                                        @input="form.image_file = $event.target.files[0]"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.image_file" />
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Guardar
                                </PrimaryButton>

                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                                </Transition>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <AlertSuccess
            v-if="fileName"
            icon="success"
            :title="fileName"
            text="Este es el nombre de la imagen guardada."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
