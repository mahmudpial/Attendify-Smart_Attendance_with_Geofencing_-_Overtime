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
    props.active ? 'nav-link nav-link--active' : 'nav-link'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<style scoped>
.nav-link {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0;
    border-bottom-width: 2px;
    border-bottom-style: solid;
    border-bottom-color: transparent;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25rem;
    color: #8b97b8;
    transition: all 0.15s ease-in-out;
    text-decoration: none;
}

.nav-link:hover {
    color: #f0f4ff;
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.nav-link--active {
    color: #4f8ef7;
    border-bottom-color: #4f8ef7;
}

.nav-link--active:hover {
    color: #4f8ef7;
    border-bottom-color: #4f8ef7;
}
</style>