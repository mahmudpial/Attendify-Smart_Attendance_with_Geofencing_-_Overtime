<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link :href="href" class="dropdown-link">
        <slot />
    </Link>
</template>

<style scoped>
.dropdown-link {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    text-align: left;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #8b97b8;
    transition: all 0.15s ease-in-out;
    text-decoration: none;
}

.dropdown-link:hover {
    background: rgba(255, 255, 255, 0.075);
    color: #f0f4ff;
}
</style>