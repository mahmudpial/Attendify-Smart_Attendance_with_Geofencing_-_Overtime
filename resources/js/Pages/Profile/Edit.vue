<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 00-3-3.87" />
                            <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">Profile</h2>
                        <p class="page-sub">Manage your account settings</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="profile-container">
            <div class="profile-card">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
            </div>

            <div class="profile-card">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="profile-card">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Dark theme tokens – matches admin dashboard */
.profile-container {
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
    background: var(--bg-dark);
    min-height: 100%;
    padding: 32px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Page header (slot) */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: var(--primary-dim);
    border: 1px solid rgba(79, 142, 247, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.page-header-icon svg {
    width: 24px;
    height: 24px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
}

.page-sub {
    font-size: 14px;
    color: var(--text-muted);
    margin-top: 2px;
}

/* Profile cards */
.profile-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 24px;
    transition: border-color 0.2s;
}

.profile-card:hover {
    border-color: var(--border-hover);
}

/* Deep overrides for child form components to match dark theme */
.profile-card :deep(.bg-white) {
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.profile-card :deep(.text-gray-700),
.profile-card :deep(.text-gray-600),
.profile-card :deep(.text-gray-800),
.profile-card :deep(.text-gray-500) {
    color: var(--text-muted) !important;
}

.profile-card :deep(label) {
    color: var(--text-muted) !important;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.profile-card :deep(input),
.profile-card :deep(select),
.profile-card :deep(textarea) {
    background: rgba(0, 0, 0, 0.3) !important;
    border: 1px solid var(--border) !important;
    border-radius: 8px !important;
    color: var(--text) !important;
}

.profile-card :deep(input:focus),
.profile-card :deep(select:focus),
.profile-card :deep(textarea:focus) {
    border-color: var(--primary) !important;
    outline: none !important;
}

.profile-card :deep(button:not(.bg-red-600):not(.bg-gray-200)) {
    background: var(--primary) !important;
    color: #0a0f1e !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
}

.profile-card :deep(button:not(.bg-red-600):not(.bg-gray-200):hover) {
    background: #3b7ae6 !important;
    transform: translateY(-1px);
}

.profile-card :deep(.bg-red-600) {
    background: rgba(248, 113, 113, 0.12) !important;
    border: 1px solid rgba(248, 113, 113, 0.3) !important;
    color: var(--danger) !important;
}

.profile-card :deep(.bg-red-600:hover) {
    background: var(--danger) !important;
    color: #0a0f1e !important;
}

.profile-card :deep(.border-gray-200),
.profile-card :deep(.border-gray-300) {
    border-color: var(--border) !important;
}

.profile-card :deep(.shadow-sm) {
    box-shadow: none !important;
}

.profile-card :deep(.rounded-lg),
.profile-card :deep(.rounded-md) {
    border-radius: 8px !important;
}

/* Responsive */
@media (max-width: 640px) {
    .profile-container {
        padding: 20px;
        gap: 16px;
    }

    .profile-card {
        padding: 18px;
    }

    .page-header-icon {
        width: 40px;
        height: 40px;
    }

    .page-header-icon svg {
        width: 20px;
        height: 20px;
    }

    .page-title {
        font-size: 20px;
    }
}
</style>