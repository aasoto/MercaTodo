<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    states: Object,
});

const form = useForm({
    name: '',
    state_id: '',
});
</script>
<template>
    <Head title="Agregar Ciudad" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agregar ciudad
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-5">
                        <form @submit.prevent="form.post(route('city.store'))" class="mt-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="col-span-1">
                                    <InputLabel for="name">
                                        Nombre<span class="text-red-600"> *</span>
                                    </InputLabel>
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autocomplete="name"
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="state_id">
                                        Estado<span class="text-red-600"> *</span>
                                    </InputLabel>
                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="state_id"
                                        id="state_id"
                                        v-model="form.state_id"
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <option
                                            v-for="state in states"
                                            :value="state.id"
                                            :key="state.id"
                                        >
                                            {{ state.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.state_id" />
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
    </AuthenticatedLayout>
</template>
