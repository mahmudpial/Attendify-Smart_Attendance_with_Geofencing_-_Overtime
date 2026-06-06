<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="input-label">
        <span v-if="value">{{ value }}</span>
        <span v-else>
            <slot />
        </span>
    </label>
</template>

<style scoped>
.input-label {
    display: block;
    font-size: 0.8125rem;
    /* 13px */
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #8b97b8;
}
</style>