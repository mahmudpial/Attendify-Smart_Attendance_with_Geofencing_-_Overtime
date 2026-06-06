<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="password-section">
        <header class="password-header">
            <h2 class="password-title">Update Password</h2>
            <p class="password-description">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="password-form">
            <div class="form-group">
                <InputLabel for="current_password" value="Current Password" class="form-label" />
                <TextInput id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                    type="password" class="form-input" autocomplete="current-password" />
                <InputError :message="form.errors.current_password" class="form-error" />
            </div>

            <div class="form-group">
                <InputLabel for="password" value="New Password" class="form-label" />
                <TextInput id="password" ref="passwordInput" v-model="form.password" type="password" class="form-input"
                    autocomplete="new-password" />
                <InputError :message="form.errors.password" class="form-error" />
            </div>

            <div class="form-group">
                <InputLabel for="password_confirmation" value="Confirm Password" class="form-label" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                    class="form-input" autocomplete="new-password" />
                <InputError :message="form.errors.password_confirmation" class="form-error" />
            </div>

            <div class="form-actions">
                <PrimaryButton :disabled="form.processing" class="save-btn">
                    Save
                </PrimaryButton>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="saved-message">
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
/* Dark theme tokens – matches parent profile page */
.password-section {
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
}

.password-header {
    margin-bottom: 24px;
}

.password-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.2px;
    margin-bottom: 6px;
}

.password-description {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
}

.password-form {
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
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
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

.form-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-top: 8px;
}

.save-btn {
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

.save-btn:hover:not(:disabled) {
    background: #3b7ae6;
    transform: translateY(-1px);
}

.saved-message {
    font-size: 13px;
    color: var(--success);
}
</style>