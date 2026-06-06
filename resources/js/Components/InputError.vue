<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="input-error">
        <p>{{ message }}</p>
    </div>
</template>

<style scoped>
.input-error {
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #f87171;
}
</style>