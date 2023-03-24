<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    cities: Array,
    mustVerifyEmail: {
        type: Boolean,
    },
    states: Array,
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    firstName: user.first_name,
    secondName: user.second_name,
    surname: user.surname,
    secondSurname: user.second_surname,
    email: user.email,
    birthdate: user.birthdate,
    gender: user.gender,
    phone: user.phone,
    address: user.address,
    state: user.state_id,
    city: user.city_id,
});

const state_selected = ref(form.state);

const show_cities = (stateId) => {
    form.city = '';
    if (stateId) {
        state_selected.value = stateId;
    } else {
        state_selected.value = 0;
    }
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-1">
                    <InputLabel for="firstName" value="Primer nombre" />

                    <TextInput
                        id="firstName"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.firstName"
                        required
                        autofocus
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
                    <InputLabel for="surname" value="Primer apellido" />

                    <TextInput
                        id="surname"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.surname"
                        autocomplete="surname"
                    />

                    <InputError class="mt-2" :message="form.errors.surname" />
                </div>

                <div class="col-span-1">
                    <InputLabel for="secondSurname" value="Segundo apellido" />

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
                    <InputLabel for="gender" value="Genero" />


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
                    <InputLabel for="birthdate" value="Fecha de nacimiento" />

                    <TextInput
                        id="birthdate"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birthdate"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.birthdate" />
                </div>

                <div class="mt-4">
                    <InputLabel for="address" value="Dirección" />

                    <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <div class="mt-4">
                    <InputLabel for="phone" value="Telefono o celular" />

                    <TextInput
                        id="phone"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div class="mt-4">
                    <InputLabel for="state" value="Estado" />

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

                <div class="mt-4" v-if="state_selected !== 0">
                    <InputLabel for="city" value="Ciudad" />

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
                                :key="city.id"
                            >
                                {{ city.name }}
                            </option>
                        </template>
                    </select>

                    <InputError class="mt-2" :message="form.errors.city" />

                </div>

                <div class="col-span-1">
                    <InputLabel for="email" value="Email" />

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
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
