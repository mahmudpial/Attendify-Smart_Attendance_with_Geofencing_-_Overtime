<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

// Safe route helper (works without Ziggy)
const safeRoute = (name, params = {}) => {
    // Try to use the global route function if available
    if (typeof route !== 'undefined' && route) {
        try {
            return route(name, params);
        } catch (e) {
            // fallback
        }
    }
    // Hardcoded fallback for common auth routes
    const fallbackRoutes = {
        'password.request': '/forgot-password',
        'register': '/register',
        'dashboard': '/dashboard',
    };
    return fallbackRoutes[name] || `/${name.replace(/\./g, '-')}`;
};

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(safeRoute('login'));
};
</script>

<template>
    <GuestLayout>

        <Head title="Log in" />

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.email" required autofocus autocomplete="username" />
                <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
            </div>

            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.password" required autocomplete="current-password" />
                <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" v-model="form.remember"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
                <Link :href="safeRoute('password.request')" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Forgot your password?
                </Link>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <Link :href="safeRoute('register')" class="text-sm text-gray-600 hover:text-gray-900 underline me-4">
                    Need an account?
                </Link>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :disabled="form.processing">
                    Log in
                </button>
            </div>
        </form>
    </GuestLayout>
</template>