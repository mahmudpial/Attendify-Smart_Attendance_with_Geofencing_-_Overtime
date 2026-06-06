<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>

        <Head title="Forgot Password" />

        <div class="forgot-container">
            <div class="forgot-header">
                <h2 class="forgot-title">Reset password</h2>
                <p class="forgot-subtitle">
                    Enter your email address and we'll send you a link to reset your password.
                </p>
            </div>

            <!-- Status message (success) -->
            <div v-if="status" class="status-message">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="forgot-form">
                <div class="form-group">
                    <InputLabel for="email" value="Email address" class="form-label" />
                    <TextInput id="email" type="email" class="form-input" v-model="form.email" required autofocus
                        autocomplete="username" placeholder="you@example.com" />
                    <InputError class="form-error" :message="form.errors.email" />
                </div>

                <div class="form-actions">
                    <PrimaryButton class="submit-btn" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        {{ form.processing ? 'Sending...' : 'Send reset link' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Dark theme tokens – matches Login and Register */
.forgot-container {
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

.forgot-header {
    text-align: center;
    margin-bottom: 28px;
}

.forgot-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
    margin-bottom: 8px;
}

.forgot-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Status message */
.status-message {
    background: rgba(52, 211, 153, 0.12);
    border: 1px solid rgba(52, 211, 153, 0.25);
    color: var(--success);
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 20px;
    text-align: center;
}

/* Form styles */
.forgot-form {
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

/* Override TextInput default styles */
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

/* Actions */
.form-actions {
    display: flex;
    justify-content: center;
    margin-top: 8px;
}

.submit-btn {
    background: var(--primary);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 700;
    color: #0a0f1e;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}

.submit-btn:hover:not(.opacity-25) {
    background: #3b7ae6;
    transform: translateY(-1px);
}
</style>