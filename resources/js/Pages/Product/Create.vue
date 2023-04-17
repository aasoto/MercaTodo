<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const editor = ClassicEditor;

const props = defineProps({
    products_categories: Object,
    units: Object,
});

const form = useForm({
    name: '',
    products_category_id: '',
    barcode: '',
    description: '',
    price: '',
    unit: '',
    stock: '',
    picture_1: '',
    picture_2: '',
    picture_3: '',
    availability: '',
});

router.post('/product', form, {
  forceFormData: true,
});

</script>
<template>
    <Head title="Agregar producto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agregar producto
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-5">
                        <form @submit.prevent="form.post(route('product.store'))" class="mt-6 space-y-6">
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
                                        autocomplete="name"
                                        autofocus
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="products_category_id">
                                        Categoría del producto<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="products_category_id"
                                        id="products_category_id"
                                        v-model="form.products_category_id"
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <option
                                            v-for="products_category in products_categories"
                                            :value="products_category.id"
                                            :key="products_category.id"
                                        >
                                            {{ products_category.name }}
                                        </option>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.products_category_id" />

                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="barcode">
                                        Código de barras<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="barcode"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.barcode"
                                        required
                                        autocomplete="barcode"
                                    />

                                    <InputError class="mt-2" :message="form.errors.barcode" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="price">
                                        Precio<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="price"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.price"
                                        required
                                        autocomplete="price"
                                    />

                                    <InputError class="mt-2" :message="form.errors.price" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="unit">
                                        Unidad<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="unit"
                                        id="unit"
                                        v-model="form.unit"
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <option
                                            v-for="unit in units"
                                            :value="unit.code"
                                            :key="unit.id"
                                        >
                                            {{ unit.name }}
                                        </option>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.unit" />

                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="stock">
                                        Stock<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="stock"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.stock"
                                        required
                                        autocomplete="stock"
                                    />

                                    <InputError class="mt-2" :message="form.errors.stock" />
                                </div>
                                <div class="col-span-2">
                                    <InputLabel for="description">
                                        Descripción
                                    </InputLabel>
                                    <ckeditor :editor="editor" v-model="form.description"></ckeditor>
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="picture_1">
                                        Foto principal<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <input
                                        type="file"
                                        name="picture_1"
                                        id="picture_1"
                                        @input="form.picture_1 = $event.target.files[0]"
                                        class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                        file:bg-transparent file:border-0
                                        file:bg-gray-200 file:mr-4
                                        file:py-3 file:px-4
                                        dark:file:bg-gray-700 dark:file:text-gray-400"
                                        required
                                    >

                                    <InputError class="mt-2" :message="form.errors.picture_1" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="picture_2">
                                        Foto alternativa 1
                                    </InputLabel>

                                    <input
                                        type="file"
                                        name="picture_2"
                                        id="picture_2"
                                        @input="form.picture_2 = $event.target.files[0]"
                                        class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                        file:bg-transparent file:border-0
                                        file:bg-gray-200 file:mr-4
                                        file:py-3 file:px-4
                                        dark:file:bg-gray-700 dark:file:text-gray-400"
                                    >

                                    <InputError class="mt-2" :message="form.errors.picture_2" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="picture_3">
                                        Foto alternativa 2
                                    </InputLabel>

                                    <input
                                        type="file"
                                        name="picture_3"
                                        id="picture_3"
                                        @input="form.picture_3 = $event.target.files[0]"
                                        class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                        file:bg-transparent file:border-0
                                        file:bg-gray-200 file:mr-4
                                        file:py-3 file:px-4
                                        dark:file:bg-gray-700 dark:file:text-gray-400"
                                    >

                                    <InputError class="mt-2" :message="form.errors.picture_3" />
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
