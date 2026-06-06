<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Confirm Password" />

        <div class="confirm-container">
            <div class="confirm-header">
                <h2 class="confirm-title">Confirm password</h2>
                <p class="confirm-subtitle">
                    This is a secure area of the application. Please confirm your password before continuing.
                </p>
            </div>

            <form @submit.prevent="submit" class="confirm-form">
                <div class="form-group">
                    <InputLabel for="password" value="Password" class="form-label" />
                    <TextInput id="password" type="password" class="form-input" v-model="form.password" required
                        autocomplete="current-password" autofocus placeholder="••••••••" />
                    <InputError class="form-error" :message="form.errors.password" />
                </div>

                <div class="form-actions">
                    <PrimaryButton class="submit-btn" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        {{ form.processing ? 'Confirming...' : 'Confirm' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Dark theme tokens – matches other auth pages */
.confirm-container {
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

.confirm-header {
    text-align: center;
    margin-bottom: 28px;
}

.confirm-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
    margin-bottom: 8px;
}

.confirm-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Form styles */
.confirm-form {
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