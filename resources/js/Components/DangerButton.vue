<template>
    <button class="danger-btn">
        <slot />
    </button>
</template>

<style scoped>
.danger-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: rgba(248, 113, 113, 0.12);
    border: 1px solid rgba(248, 113, 113, 0.3);
    color: #f87171;
    transition: all 0.2s ease;
    cursor: pointer;
}

.danger-btn:hover {
    background: #f87171;
    border-color: #f87171;
    color: #0a0f1e;
    transform: translateY(-1px);
}

.danger-btn:focus {
    outline: none;
    ring: 2px solid #f87171;
    ring-offset: 2px;
}

.danger-btn:active {
    background: #ef4444;
    transform: translateY(0);
}

.danger-btn:disabled {
    opacity: 0.25;
    cursor: not-allowed;
    transform: none;
}
</style>