<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const canLogin = page.props.canLogin ?? false;
const canRegister = page.props.canRegister ?? false;
const laravelVersion = page.props.laravelVersion ?? '';
const phpVersion = page.props.phpVersion ?? '';
const auth = page.props.auth ?? { user: null };

// Safe route function – falls back to plain URLs if Ziggy is not available
const safeRoute = (name, params = {}) => {
    if (typeof route !== 'undefined') {
        try {
            return route(name, params);
        } catch (e) {
            console.warn(`Ziggy route "${name}" not found, falling back.`);
            return `/${name}`;
        }
    }
    return `/${name}`;
};

const dashboardUrl = computed(() => safeRoute('dashboard'));
const loginUrl = computed(() => safeRoute('login'));
const registerUrl = computed(() => safeRoute('register'));
</script>

<template>
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="auth.user" :href="dashboardUrl" class="text-sm text-gray-700 dark:text-gray-500 underline">
                Dashboard
            </Link>
            <template v-else>
                <Link :href="loginUrl" class="text-sm text-gray-700 dark:text-gray-500 underline">
                    Log in
                </Link>
                <Link v-if="canRegister" :href="registerUrl"
                    class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                    Attendance System
                </h1>
            </div>
            <div class="mt-8 text-center text-gray-600 dark:text-gray-400">
                <p>Welcome to the Smart Attendance System with Geofencing & Overtime.</p>
                <p class="mt-2">Laravel v{{ laravelVersion }} | PHP v{{ phpVersion }}</p>
            </div>
            <div class="mt-8 flex justify-center">
                <Link :href="loginUrl" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Get Started
                </Link>
            </div>
        </div>
    </div>
</template>