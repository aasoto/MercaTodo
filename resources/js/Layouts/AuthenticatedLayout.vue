<script setup>
import { ref } from 'vue';
import { storeToRefs } from 'pinia';

import { Link } from '@inertiajs/vue3';
import { useSignedRoleStore } from '@/Store/SignedRole';

import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import MercaTodoLogoGray from "@/Components/Images/MercaTodoLogoGray.vue";
import MercaTodoLogoWhite from "@/Components/Images/MercaTodoLogoWhite.vue";
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { useCartStore } from '@/Store/Cart';

const showingNavigationDropdown = ref(false);

const useSignedRole = useSignedRoleStore();
const { role } = storeToRefs(useSignedRole);
const { unassignRole } = useSignedRole;

const useCart = useCartStore();
const { numberOfProducts, numberQuantityOfProducts } = storeToRefs(useCart);

</script>

<template>
    <div class="relative">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('start')">
                                    <MercaTodoLogoGray class="block dark:hidden h-9 w-auto"/>
                                    <MercaTodoLogoWhite class="hidden dark:block h-9 w-auto"/>
                                </Link>
                            </div>
                            <div v-if="!$page.props.auth.user" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('root')" :active="route().current('root')">
                                    Inicio
                                </NavLink>
                            </div>
                            <!-- Navigation Links -->
                            <template v-if="role == 'admin'">
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink :href="route('dashboard.index')" :active="route().current('dashboard')">
                                        Dashboard
                                    </NavLink>
                                </div>
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink :href="route('user.index', 'admin')" :active="route().current('user.index')">
                                        Usuarios
                                    </NavLink>
                                </div>
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink :href="route('products.index')" :active="route().current('products.index')">
                                        Productos
                                    </NavLink>
                                </div>
                            </template>
                            <template v-if="role == 'client'">
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink :href="route('showcase.index')" :active="route().current('showcase.index')">
                                        Vitrina
                                    </NavLink>
                                </div>
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink :href="route('order.index')" :active="route().current('order.index')">
                                        Ordenes
                                    </NavLink>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-5">
                            <div v-if="role == 'client'" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('order.create')" :active="route().current('order.create')">
                                    <div
                                        class="relative h-12 w-12 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full"
                                    >
                                        <svg
                                            v-if="!numberOfProducts"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-9 h-9 stroke-red-500"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="w-9 h-9 text-red-500"
                                        >
                                            <path
                                                d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                            />
                                        </svg>
                                        <span
                                            class="absolute translate-x-0.5 -translate-y-0.5 z-20 text-xs font-bold"
                                            :class="numberQuantityOfProducts ? 'text-white' : 'text-red-500'"
                                        >
                                            {{ numberQuantityOfProducts }}
                                        </span>
                                        <span
                                            v-if="numberOfProducts"
                                            class="absolute right-0 top-0 translate-x-3 -translate-y-1 text-xs font-bold px-1.5 py-0.5 h-max rounded bg-red-600 text-white"
                                        >
                                            {{ numberOfProducts }}
                                        </span>
                                    </div>
                                </NavLink>
                            </div>
                            <div class="flex justify-end items-center">
                                <div class="hidden sm:flex sm:items-center sm:ml-6">
                                    <!-- Settings Dropdown -->
                                    <div v-if="$page.props.auth.user" class="ml-3 relative">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button
                                                        type="button"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                                    >
                                                        {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.surname }}

                                                        <svg
                                                            class="ml-2 -mr-0.5 h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content>
                                                <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                                                <a
                                                    v-if="role == 'admin'"
                                                    href="/log-viewer"
                                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                                                >
                                                    Logs
                                                </a>
                                                <DropdownLink :href="route('logout')" @click="unassignRole()" method="post" as="button">
                                                    Cerrar sesión
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                    <div v-else class="flex justify-center items-center gap-5">
                                        <Link
                                            :href="route('login')"
                                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                        >
                                            Iniciar sesión
                                        </Link>
                                        <Link
                                            :href="route('register')"
                                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                        >
                                            Registarse
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <template v-if="role == 'admin'">
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard.index')" :active="route().current('dashboard')">
                                Dashboard
                            </ResponsiveNavLink>
                        </div>
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('user.index', 'admin')" :active="route().current('user.index')">
                                Usuarios
                            </ResponsiveNavLink>
                        </div>
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('products.index')" :active="route().current('products.index')">
                                Productos
                            </ResponsiveNavLink>
                        </div>
                    </template>
                    <template v-if="role == 'client'">
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('showcase.index')" :active="route().current('showcase.index')">
                                Vitrina
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('order.index')" :active="route().current('order.index')">
                                Ordenes
                            </ResponsiveNavLink>
                        </div>
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink
                                :href="route('order.create')"
                                :active="route().current('order.create')"
                                class="flex justify-start items-center gap-3"
                            >
                                <div
                                    class="h-12 w-12 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full"
                                >
                                    <svg
                                        v-if="!numberOfProducts"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-7 h-7 stroke-red-500"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        class="w-7 h-7 text-red-500"
                                    >
                                        <path
                                            d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                        />
                                    </svg>
                                </div>
                                <span
                                    v-if="numberOfProducts"
                                    class="text-xs font-bold px-1.5 py-0.5 h-max rounded bg-red-600 text-white"
                                >
                                    {{ numberOfProducts }}
                                </span>
                            </ResponsiveNavLink>
                        </div>
                    </template>
                    <!-- Responsive Settings Options -->
                    <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.surname }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Perfil </ResponsiveNavLink>
                            <a
                                v-if="role == 'admin'"
                                href="/log-viewer"
                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                            >
                                Logs
                            </a>
                            <ResponsiveNavLink :href="route('logout')" @click="unassignRole()" method="post" as="button">
                                Cerrar sesión
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div v-else class="flex justify-center items-center gap-5">
                        <Link
                            :href="route('login')"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >
                            Iniciar sesión
                        </Link>
                        <Link
                            :href="route('register')"
                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >
                            Registarse
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
