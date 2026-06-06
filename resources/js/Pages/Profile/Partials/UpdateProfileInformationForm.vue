<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="profile-section">
        <header class="profile-header">
            <h2 class="profile-title">Profile Information</h2>
            <p class="profile-description">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="profile-form">
            <div class="form-group">
                <InputLabel for="name" value="Name" class="form-label" />
                <TextInput id="name" type="text" class="form-input" v-model="form.name" required autofocus
                    autocomplete="name" />
                <InputError class="form-error" :message="form.errors.name" />
            </div>

            <div class="form-group">
                <InputLabel for="email" value="Email" class="form-label" />
                <TextInput id="email" type="email" class="form-input" v-model="form.email" required
                    autocomplete="username" />
                <InputError class="form-error" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="verification-message">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button" class="resend-link">
                        Click here to re-send the verification email.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="success-message">
                    A new verification link has been sent to your email address.
                </div>
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
.profile-section {
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

.profile-header {
    margin-bottom: 24px;
}

.profile-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.2px;
    margin-bottom: 6px;
}

.profile-description {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
}

.profile-form {
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

.verification-message {
    font-size: 13px;
    color: var(--text-muted);
    background: rgba(0, 0, 0, 0.2);
    padding: 10px 12px;
    border-radius: 8px;
    border-left: 3px solid var(--warning);
    margin-top: 8px;
}

.resend-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    margin-left: 4px;
}

.resend-link:hover {
    color: #3b7ae6;
}

.success-message {
    background: rgba(52, 211, 153, 0.12);
    border: 1px solid rgba(52, 211, 153, 0.25);
    color: var(--success);
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    margin-top: 10px;
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