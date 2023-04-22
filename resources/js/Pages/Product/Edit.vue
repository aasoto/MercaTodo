<script setup>
import InputError from '@/Components/InputError.vue';
import ImageFormatError from './Partials/ImageFormatError.vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { reactive, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import { useShowImage } from "@/Composables/showImage";

const editor = ClassicEditor;

const props = defineProps({
    errors: Object,
    product: Object,
    products_categories: Object,
    success: String,
    units: Object,
});

const form = reactive({
    name: props.product.name,
    products_category_id: props.product.products_category_id,
    barcode: props.product.barcode,
    description: props.product.description,
    price: props.product.price,
    unit: props.product.unit,
    stock: props.product.stock,
    picture_1: '',
    picture_2: '',
    picture_3: '',
    availability: props.product.availability == 1 ? true : false,
});


const changeAvailability = () => {
    form.availability = !form.availability;
}

const currentFiles = ref({
    picture_1: props.product.picture_1,
    picture_2: props.product.picture_2,
    picture_3: props.product.picture_3,
});

const picture1Charged = ref(false);
const picture1Error = ref(false);

const validateAttachPicture1 = () => {

    const picture1 = useShowImage(
        form.picture_1,
        document.getElementById('picture_1'),
        document.getElementById('show_picture_1')
    );

    form.picture_1 = picture1.image;
    picture1Charged.value = picture1.charged;
    picture1Error.value = picture1.error;
}

const picture2Charged = ref(false);
const picture2Error = ref(false);

const validateAttachPicture2 = () => {

    const picture2 = useShowImage(
        form.picture_2,
        document.getElementById('picture_2'),
        document.getElementById('show_picture_2')
    );

    form.picture_2 = picture2.image;
    picture2Charged.value = picture2.charged;
    picture2Error.value = picture2.error;
}

const picture3Charged = ref(false);
const picture3Error = ref(false);

const validateAttachPicture3 = () => {


    const picture3 = useShowImage(
        form.picture_3,
        document.getElementById('picture_3'),
        document.getElementById('show_picture_3')
    );

    form.picture_3 = picture3.image;
    picture3Charged.value = picture3.charged;
    picture3Error.value = picture3.error;
}

const submit = () => {
    router.post(`/product/edit/${props.product.id}/${JSON.stringify(currentFiles.value)}`, {
        _method: 'patch',
        name: form.name,
        products_category_id: form.products_category_id,
        barcode: form.barcode,
        description: form.description,
        price: form.price,
        unit: form.unit,
        stock: form.stock,
        picture_1: form.picture_1,
        picture_2: form.picture_2,
        picture_3: form.picture_3,
        availability: form.availability,
    });
}


</script>
<template>
    <Head title="Editar producto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar producto
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-5">
                        <form @submit.prevent="submit" class="mt-6 space-y-6">
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
                                    <InputError class="mt-2" :message="errors.name" />
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

                                    <InputError class="mt-2" :message="errors.products_category_id" />

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

                                    <InputError class="mt-2" :message="errors.barcode" />
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="price">
                                        Precio<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="price"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.price"
                                        required
                                        autocomplete="price"
                                    />
                                    <InputError class="mt-2" :message="errors.price" />
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

                                    <InputError class="mt-2" :message="errors.unit" />

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

                                    <InputError class="mt-2" :message="errors.stock" />
                                </div>
                                <div class="col-span-1 flex justify-center items-center gap-5">
                                    <div
                                        @click="changeAvailability()"
                                        :class="form.availability ?
                                            'bg-green-600 text-white px-4 py-1 flex gap-4 rounded cursor-pointer' :
                                            'bg-red-600 text-white px-4 py-1 flex gap-4 rounded cursor-pointer'"
                                    >
                                        <template v-if="form.availability">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                            </svg> Disponible
                                        </template>
                                        <template v-else>
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
                                            </svg> No disponible
                                        </template>
                                    </div>
                                    <InputError class="mt-2" :message="errors.availability" />
                                </div>
                                <div class="col-span-2">
                                    <InputLabel for="description">
                                        Descripción
                                    </InputLabel>
                                    <ckeditor :editor="editor" v-model="form.description"></ckeditor>
                                </div>
                                <div class="col-span-1">
                                    <InputLabel for="picture_1">
                                        Foto principal
                                    </InputLabel>

                                    <input
                                        type="file"
                                        name="picture_1"
                                        id="picture_1"
                                        @input="form.picture_1 = $event.target.files[0]"
                                        @change="validateAttachPicture1()"
                                        class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                        file:bg-transparent file:border-0
                                        file:bg-gray-200 file:mr-4
                                        file:py-3 file:px-4
                                        dark:file:bg-gray-700 dark:file:text-gray-400"
                                    >

                                    <InputError class="mt-2" :message="errors.picture_1" />
                                    <ImageFormatError v-show="picture1Error"/>
                                    <img
                                        id="show_picture_1"
                                        class="mt-2 mx-auto"
                                        :src="`../../images/products/${currentFiles.picture_1}`"
                                        alt="product_photo_1"
                                    >
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
                                        @change="validateAttachPicture2()"
                                        class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                        file:bg-transparent file:border-0
                                        file:bg-gray-200 file:mr-4
                                        file:py-3 file:px-4
                                        dark:file:bg-gray-700 dark:file:text-gray-400"
                                    >

                                    <InputError class="mt-2" :message="errors.picture_2" />
                                    <ImageFormatError v-show="picture2Error"/>
                                    <img
                                        v-show="currentFiles.picture_2 || picture2Charged"
                                        id="show_picture_2"
                                        class="mt-2 mx-auto"
                                        :src="currentFiles.picture_2 && `../../images/products/${currentFiles.picture_2}`"
                                        alt="product_photo_2"
                                    >
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
                                        @change="validateAttachPicture3()"
                                        class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400
                                        file:bg-transparent file:border-0
                                        file:bg-gray-200 file:mr-4
                                        file:py-3 file:px-4
                                        dark:file:bg-gray-700 dark:file:text-gray-400"
                                    >

                                    <InputError class="mt-2" :message="errors.picture_3" />
                                    <ImageFormatError v-show="picture3Error"/>
                                    <img
                                        v-show="currentFiles.picture_3 || picture3Charged"
                                        id="show_picture_3"
                                        class="mt-2 mx-auto"
                                        :src="currentFiles.picture_3 && `../../images/products/${currentFiles.picture_3}`"
                                        alt="product_photo_3"
                                    >
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
            v-if="success === 'Product updated.'"
            title="¡Bien Hecho!"
            text="Producto actualizado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>
