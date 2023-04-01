<script setup>
import RegisterUserLayout from "@/Layouts/RegisterUserLayout.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    cities: Array,
    states: Array,
});

const form = useForm({
    // name: '',
    typeDoc: '',
    numDoc: '',
    firstName: '',
    secondName: '',
    surname: '',
    secondSurname: '',
    birthdate: '',
    gender: '',
    phone: '',
    address: '',
    email: '',
    state: '',
    city: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const state_selected = ref(0);

const show_cities = (stateId) => {
    form.city = '';
    if (stateId) {
        state_selected.value = stateId;
    } else {
        state_selected.value = 0;
    }
}

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <RegisterUserLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="col-span-1">
                    <InputLabel for="typeDoc">
                        Tipo de documento<span class="text-red-600"> *</span>
                    </InputLabel>

                    <select
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        name="typeDoc"
                        id="typeDoc"
                        v-model="form.typeDoc"
                        autofocus
                        required
                    >
                        <option value="">Seleccionar...</option>
                        <option value="cc">Cédula de ciudadanía</option>
                        <option value="pas">Pasaporte</option>
                        <option value="o">Otro</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.typeDoc" />

                </div>

                <div class="col-span-1">
                    <InputLabel for="numDoc">
                        Número de documento<span class="text-red-600"> *</span>
                    </InputLabel>
                    <TextInput
                        id="numDoc"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.numDoc"
                        required
                        autocomplete="numDoc"
                    />

                    <InputError class="mt-2" :message="form.errors.numDoc" />
                </div>

                <div class="col-span-1">
                    <InputLabel for="firstName">
                        Primer nombre<span class="text-red-600"> *</span>
                    </InputLabel>
                    <TextInput
                        id="firstName"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.firstName"
                        required
                        autocomplete="firstName"
                    />

                    <InputError class="mt-2" :message="form.errors.firstName" />
                </div>

                <div class="col-span-1">
                    <InputLabel for="secondName" value="Segundo nombre" />

                    <TextInput
                        id="secondName"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.secondName"
                        autocomplete="secondName"
                    />

                    <InputError class="mt-2" :message="form.errors.secondName" />
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
                        required
                        autocomplete="surname"
                    />

                    <InputError class="mt-2" :message="form.errors.surname" />
                </div>

                <div class="col-span-1">
                    <InputLabel for="secondSurname" value="Segundo Apellido" />

                    <TextInput
                        id="secondSurname"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.secondSurname"
                        autocomplete="secondSurname"
                    />

                    <InputError class="mt-2" :message="form.errors.secondSurname" />
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
                        Fecha nacimiento<span class="text-red-600"> *</span>
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
                    <InputLabel for="state">
                        Estado<span class="text-red-600"> *</span>
                    </InputLabel>

                    <select
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        name="state"
                        id="state"
                        v-model="form.state"
                        @change="show_cities(form.state)"
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

                    <InputError class="mt-2" :message="form.errors.state" />

                </div>

                <div class="col-span-1" v-if="state_selected !== 0">
                    <InputLabel for="city">
                        Ciudad<span class="text-red-600"> *</span>
                    </InputLabel>

                    <select
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        name="city"
                        id="city"
                        v-model="form.city"
                        required
                    >
                        <option value="">Seleccionar...</option>
                        <template v-for="city in cities">
                            <option
                                v-if="city.state_id == state_selected"
                                :value="city.id"
                            >
                                {{ city.name }}
                            </option>
                        </template>
                    </select>

                    <InputError class="mt-2" :message="form.errors.city" />

                </div>

                <div class="col-span-1">
                    <InputLabel for="email">
                        Correo Electronico<span class="text-red-600"> *</span>
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
                    <InputLabel for="password">
                        Contraseña<span class="text-red-600"> *</span>
                    </InputLabel>

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="col-span-1">
                    <InputLabel for="password_confirmation">
                        Confirmación de contreseña<span class="text-red-600"> *</span>
                    </InputLabel>

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    ¿Ya tienes una cuenta?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registarse
                </PrimaryButton>
            </div>
        </form>
    </RegisterUserLayout>
</template>
