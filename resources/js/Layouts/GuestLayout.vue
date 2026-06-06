<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="guest-layout">
        <div class="guest-container">
            <!-- Logo -->
            <div class="logo-wrapper">
                <Link href="/">
                    <ApplicationLogo class="logo" />
                </Link>
            </div>

            <!-- Card (slot container) -->
            <div class="guest-card">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Dark theme tokens – matches Welcome.vue */
.guest-layout {
    --bg-dark: #0a0f1e;
    --surface: rgba(255, 255, 255, 0.045);
    --surface-hover: rgba(255, 255, 255, 0.075);
    --border-light: rgba(255, 255, 255, 0.08);
    --text-primary: #f0f4ff;
    --text-muted: #8b97b8;
    --primary: #4f8ef7;
    --primary-dim: rgba(79, 142, 247, 0.15);
}

.guest-layout {
    min-height: 100vh;
    background-color: var(--bg-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

.guest-container {
    width: 100%;
    max-width: 28rem;
    /* equivalent to sm:max-w-md */
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-wrapper {
    margin-bottom: 1.5rem;
}

.logo {
    height: 5rem;
    /* 20 * 0.25rem = 5rem */
    width: 5rem;
    fill: currentColor;
    color: var(--primary);
    transition: color 0.2s ease;
}

.logo:hover {
    color: var(--text-primary);
}

.guest-card {
    width: 100%;
    background: var(--surface);
    backdrop-filter: blur(12px);
    border: 1px solid var(--border-light);
    border-radius: 1rem;
    /* 16px */
    padding: 1.5rem;
    /* 24px */
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
    transition: border-color 0.2s, background 0.2s;
}

.guest-card:hover {
    border-color: rgba(79, 142, 247, 0.4);
    background: var(--surface-hover);
}

/* Ensure all text inside the card uses the theme */
.guest-card :deep(*) {
    color: var(--text-primary);
}

.guest-card :deep(input),
.guest-card :deep(button) {
    color: #1a1a2e;
    /* keep readable for inputs/buttons – they have their own bg */
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .guest-layout {
        padding: 1rem;
    }

    .guest-card {
        padding: 1.25rem;
    }

    .logo {
        height: 3.5rem;
        width: 3.5rem;
    }
}
</style>