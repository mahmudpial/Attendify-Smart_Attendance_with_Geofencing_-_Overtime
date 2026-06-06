<template>
    <button class="primary-btn">
        <slot />
    </button>
</template>

<style scoped>
.primary-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: #4f8ef7;
    border: none;
    color: #0a0f1e;
    transition: all 0.2s ease;
    cursor: pointer;
}

.primary-btn:hover {
    background: #3b7ae6;
    transform: translateY(-1px);
}

.primary-btn:focus {
    outline: none;
    ring: 2px solid #4f8ef7;
    ring-offset: 2px;
}

.primary-btn:active {
    background: #2a6ac8;
    transform: translateY(0);
}

.primary-btn:disabled {
    opacity: 0.25;
    cursor: not-allowed;
    transform: none;
}
</style>