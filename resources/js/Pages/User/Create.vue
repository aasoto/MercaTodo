<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    cities: Array,
    roles: Array,
    states: Array,
    typeDocuments: Object,
});

const form = useForm({
    type_document: '',
    number_document: '',
    first_name: '',
    second_name: '',
    surname: '',
    second_surname: '',
    email: '',
    birthdate: '',
    gender: '',
    phone: '',
    address: '',
    state_id: '',
    city_id: '',
    role_id: '',
});

const state_selected = ref(0);

const show_cities = (stateId) => {
    form.city_id = '';
    if (stateId) {
        state_selected.value = stateId;
    } else {
        state_selected.value = 0;
    }
}
</script>
<template>
    <Head title="Agregar usuario" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agregar usuario
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-5">
                        <form @submit.prevent="form.post(route('user.store'))" class="mt-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div class="col-span-1">
                                    <InputLabel for="type_document">
                                        Tipo de documento<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="type_document"
                                        id="type_document"
                                        v-model="form.type_document"
                                        autofocus
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <option
                                            v-for="typeDocument in typeDocuments"
                                            :value="typeDocument.code"
                                            :key="typeDocument.id"
                                        >
                                            {{ typeDocument.name }}
                                        </option>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.type_document" />

                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="number_document">
                                        Número de documento<span class="text-red-600"> *</span>
                                    </InputLabel>
                                    <TextInput
                                        id="number_document"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.number_document"
                                        required
                                        autocomplete="number_document"
                                    />

                                    <InputError class="mt-2" :message="form.errors.number_document" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="first_name">
                                        Primer nombre<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="first_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.first_name"
                                        required
                                        autocomplete="first_name"
                                    />

                                    <InputError class="mt-2" :message="form.errors.first_name" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="second_name" value="Segundo nombre" />

                                    <TextInput
                                        id="second_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.second_name"
                                        autocomplete="second_name"
                                    />

                                    <InputError class="mt-2" :message="form.errors.second_name" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="surname">
                                        Primer apellido<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="surname"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.surname"
                                        autocomplete="surname"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.surname" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="second_surname" value="Segundo apellido" />

                                    <TextInput
                                        id="second_surname"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.second_surname"
                                        autocomplete="second_surname"
                                    />

                                    <InputError class="mt-2" :message="form.errors.second_surname" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="gender">
                                        Genero<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="gender"
                                        id="gender"
                                        v-model="form.gender"
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <option value="m">Masculino</option>
                                        <option value="f">Femenino</option>
                                        <option value="o">Otro</option>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.gender" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="birthdate">
                                        Fecha de nacimiento<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="birthdate"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.birthdate"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.birthdate" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="address">
                                        Dirección<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="address"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.address"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.address" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="phone">
                                        Telefono o celular<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.phone"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.phone" />
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
                                        @change="show_cities(form.state_id)"
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

                                <div class="col-span-1" v-if="state_selected !== 0">
                                    <InputLabel for="city_id">
                                        Ciudad<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="city_id"
                                        id="city_id"
                                        v-model="form.city_id"
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <template v-for="city in cities">
                                            <option
                                                v-if="city.state_id == state_selected"
                                                :value="city.id"
                                                :key="city.id"
                                            >
                                                {{ city.name }}
                                            </option>
                                        </template>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.city_id" />

                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="email">
                                        Correo electronico<span class="text-red-600"> *</span>
                                    </InputLabel>

                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                        required
                                        autocomplete="username"
                                    />

                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="role_id">
                                        Rol<span class="text-red-600"> *</span>
                                    </InputLabel>


                                    <select
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        name="role_id"
                                        id="role_id"
                                        v-model="form.role_id"
                                        required
                                    >
                                        <option value="">Seleccionar...</option>
                                        <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.role_id" />
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
