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

        <div class="login-container">
            <div class="login-header">
                <h2 class="login-title">Welcome back</h2>
                <p class="login-subtitle">Sign in to your account</p>
            </div>

            <form @submit.prevent="submit" class="login-form">
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" class="form-input" v-model="form.email" required autofocus
                        autocomplete="username" placeholder="you@example.com" />
                    <div v-if="form.errors.email" class="form-error">{{ form.errors.email }}</div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-input" v-model="form.password" required
                        autocomplete="current-password" placeholder="••••••••" />
                    <div v-if="form.errors.password" class="form-error">{{ form.errors.password }}</div>
                </div>

                <!-- Remember & Forgot Password -->
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" v-model="form.remember" class="checkbox" />
                        <span>Remember me</span>
                    </label>
                    <Link :href="safeRoute('password.request')" class="forgot-link">
                        Forgot password?
                    </Link>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn" :disabled="form.processing">
                    {{ form.processing ? 'Signing in...' : 'Sign in' }}
                </button>

                <!-- Register Link -->
                <div class="register-link">
                    <span>Don't have an account?</span>
                    <Link :href="safeRoute('register')">Create an account</Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Dark theme tokens – matches the GuestLayout redesign */
.login-container {
    --bg-dark: #0a0f1e;
    --surface: rgba(255, 255, 255, 0.045);
    --surface-hover: rgba(255, 255, 255, 0.075);
    --border: rgba(255, 255, 255, 0.08);
    --border-hover: rgba(255, 255, 255, 0.16);
    --text: #f0f4ff;
    --text-muted: #8b97b8;
    --text-disabled: #545e7a;
    --primary: #4f8ef7;
    --primary-dim: rgba(79, 142, 247, 0.15);
    --danger: #f87171;
    --success: #34d399;
    --warning: #fbbf24;
    --info: #a78bfa;
    --r: 12px;
    --font: 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif;

    font-family: var(--font);
}

.login-header {
    text-align: center;
    margin-bottom: 28px;
}

.login-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
    margin-bottom: 6px;
}

.login-subtitle {
    font-size: 14px;
    color: var(--text-muted);
}

/* Form elements */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-input {
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 14px;
    transition: all 0.2s;
    /* Explicit text colour */
    color: var(--text);
}

/* Override autofill styles */
.form-input:-webkit-autofill,
.form-input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5) inset !important;
    -webkit-text-fill-color: var(--text) !important;
    caret-color: var(--text) !important;
    border-color: var(--border);
}

/* Placeholder contrast */
.form-input::placeholder {
    color: var(--text-muted);
    opacity: 0.7;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.5);
}

.form-error {
    font-size: 12px;
    color: var(--danger);
    margin-top: 4px;
}

/* Remember & Forgot row */
.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 4px 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-muted);
    cursor: pointer;
}

.checkbox {
    width: 16px;
    height: 16px;
    accent-color: var(--primary);
    cursor: pointer;
}

.forgot-link {
    font-size: 13px;
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s;
}

.forgot-link:hover {
    color: #3b7ae6;
}

/* Submit button */
.submit-btn {
    width: 100%;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-size: 15px;
    font-weight: 700;
    color: #0a0f1e;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 8px;
}

.submit-btn:hover:not(:disabled) {
    background: #3b7ae6;
    transform: translateY(-1px);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Register link */
.register-link {
    text-align: center;
    font-size: 13px;
    color: var(--text-muted);
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.register-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    margin-left: 6px;
    transition: color 0.2s;
}

.register-link a:hover {
    color: #3b7ae6;
}
</style>