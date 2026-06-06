<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'responsive-nav-link active'
        : 'responsive-nav-link'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<style scoped>
.responsive-nav-link {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 0.75rem;
    border-left-width: 4px;
    border-left-style: solid;
    border-left-color: transparent;
    text-align: left;
    font-size: 1rem;
    font-weight: 500;
    color: #8b97b8;
    background: transparent;
    transition: all 0.15s ease-in-out;
}

.responsive-nav-link:hover {
    color: #f0f4ff;
    background: rgba(255, 255, 255, 0.05);
    border-left-color: rgba(255, 255, 255, 0.2);
}

.responsive-nav-link.active {
    border-left-color: #4f8ef7;
    color: #4f8ef7;
    background: rgba(79, 142, 247, 0.08);
}

.responsive-nav-link:focus {
    outline: none;
    color: #f0f4ff;
    background: rgba(255, 255, 255, 0.08);
    border-left-color: rgba(79, 142, 247, 0.5);
}
</style>