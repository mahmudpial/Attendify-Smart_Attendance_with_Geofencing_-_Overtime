<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="secondary-btn">
        <slot />
    </button>
</template>

<style scoped>
.secondary-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: #f0f4ff;
    transition: all 0.2s ease;
    cursor: pointer;
}

.secondary-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.24);
    transform: translateY(-1px);
}

.secondary-btn:focus {
    outline: none;
    ring: 2px solid #4f8ef7;
    ring-offset: 2px;
}

.secondary-btn:disabled {
    opacity: 0.25;
    cursor: not-allowed;
    transform: none;
}
</style>