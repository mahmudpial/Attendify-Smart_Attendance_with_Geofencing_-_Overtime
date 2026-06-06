<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <div class="register-container">
            <div class="register-header">
                <h2 class="register-title">Create account</h2>
                <p class="register-subtitle">Join us to start tracking attendance</p>
            </div>

            <form @submit.prevent="submit" class="register-form">
                <!-- Name Field -->
                <div class="form-group">
                    <InputLabel for="name" value="Full name" class="form-label" />
                    <TextInput id="name" type="text" class="form-input" v-model="form.name" required autofocus
                        autocomplete="name" placeholder="John Doe" />
                    <InputError class="form-error" :message="form.errors.name" />
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <InputLabel for="email" value="Email address" class="form-label" />
                    <TextInput id="email" type="email" class="form-input" v-model="form.email" required
                        autocomplete="username" placeholder="you@example.com" />
                    <InputError class="form-error" :message="form.errors.email" />
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <InputLabel for="password" value="Password" class="form-label" />
                    <TextInput id="password" type="password" class="form-input" v-model="form.password" required
                        autocomplete="new-password" placeholder="••••••••" />
                    <InputError class="form-error" :message="form.errors.password" />
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <InputLabel for="password_confirmation" value="Confirm password" class="form-label" />
                    <TextInput id="password_confirmation" type="password" class="form-input"
                        v-model="form.password_confirmation" required autocomplete="new-password"
                        placeholder="••••••••" />
                    <InputError class="form-error" :message="form.errors.password_confirmation" />
                </div>

                <!-- Submit & Login Link -->
                <div class="form-actions">
                    <Link :href="route('login')" class="login-link">
                        Already registered?
                    </Link>
                    <PrimaryButton class="submit-btn" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        {{ form.processing ? 'Creating account...' : 'Sign up' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Dark theme tokens – matches Login and GuestLayout */
.register-container {
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

.register-header {
    text-align: center;
    margin-bottom: 28px;
}

.register-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
    margin-bottom: 6px;
}

.register-subtitle {
    font-size: 14px;
    color: var(--text-muted);
}

/* Form styles */
.register-form {
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

/* Override TextInput and InputError default styles */
.form-input {
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 14px;
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.5);
}

.form-input::placeholder {
    color: var(--text-disabled);
}

.form-error {
    font-size: 12px;
    color: var(--danger);
    margin-top: 4px;
}

/* Actions row */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 12px;
}

.login-link {
    font-size: 13px;
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s;
}

.login-link:hover {
    color: #3b7ae6;
}

.submit-btn {
    background: var(--primary);
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 700;
    color: #0a0f1e;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-btn:hover:not(.opacity-25) {
    background: #3b7ae6;
    transform: translateY(-1px);
}
</style>