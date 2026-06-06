<template>
    <div v-if="links.length > 3" class="pagination-container">
        <template v-for="(link, index) in links" :key="index">
            <Link v-if="link.url" :href="link.url" class="pagination-link"
                :class="{ 'pagination-link--active': link.active }" v-html="link.label" />
            <span v-else class="pagination-link pagination-link--disabled" v-html="link.label" />
        </template>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
.pagination-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.045);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #8b97b8;
    text-decoration: none;
    transition: all 0.2s ease;
}

.pagination-link:hover {
    background: rgba(255, 255, 255, 0.075);
    border-color: rgba(255, 255, 255, 0.16);
    color: #f0f4ff;
    transform: translateY(-1px);
}

.pagination-link--active {
    background: #4f8ef7;
    border-color: #4f8ef7;
    color: #0a0f1e;
    font-weight: 700;
    box-shadow: 0 0 8px rgba(79, 142, 247, 0.4);
}

.pagination-link--active:hover {
    background: #3b7ae6;
    border-color: #3b7ae6;
    transform: translateY(-1px);
}

.pagination-link--disabled {
    background: rgba(255, 255, 255, 0.02);
    border-color: rgba(255, 255, 255, 0.04);
    color: #545e7a;
    cursor: default;
}

.pagination-link--disabled:hover {
    transform: none;
    background: rgba(255, 255, 255, 0.02);
    color: #545e7a;
}
</style>