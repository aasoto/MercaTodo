<script setup>
import MercaTodoLogoGray from "@/Components/Images/MercaTodoLogoGray.vue";
import { Link } from '@inertiajs/vue3';
import MercaTodoLogoWhite from '@/Components/Images/MercaTodoLogoWhite.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <Link href="/">
                <MercaTodoLogoGray class="block dark:hidden w-28 h-28 fill-current text-gray-500" />
                <MercaTodoLogoWhite class="hidden dark:block w-28 h-28 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
