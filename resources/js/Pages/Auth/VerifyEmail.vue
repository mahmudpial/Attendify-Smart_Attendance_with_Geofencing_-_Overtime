<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>

        <Head title="Email Verification" />

        <div class="verify-container">
            <div class="verify-header">
                <h2 class="verify-title">Verify your email</h2>
                <p class="verify-subtitle">
                    Thanks for signing up! Before getting started, could you verify your
                    email address by clicking on the link we just emailed to you?
                </p>
            </div>

            <!-- Success message -->
            <div v-if="verificationLinkSent" class="success-message">
                A new verification link has been sent to the email address you provided.
            </div>

            <form @submit.prevent="submit" class="verify-form">
                <div class="form-actions">
                    <PrimaryButton class="resend-btn" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        {{ form.processing ? 'Sending...' : 'Resend verification email' }}
                    </PrimaryButton>

                    <Link :href="route('logout')" method="post" as="button" class="logout-link">
                        Log out
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
<style scoped>
/* Dark theme tokens – matches other auth pages */
.verify-container {
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

.verify-header {
    text-align: center;
    margin-bottom: 28px;
}

.verify-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
    margin-bottom: 8px;
}

.verify-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Success message */
.success-message {
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

/* Form actions */
.verify-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.resend-btn {
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

.resend-btn:hover:not(.opacity-25) {
    background: #3b7ae6;
    transform: translateY(-1px);
}

.logout-link {
    background: none;
    border: none;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted);
    text-decoration: none;
    transition: color 0.2s;
    cursor: pointer;
}

.logout-link:hover {
    color: var(--danger);
}
</style>