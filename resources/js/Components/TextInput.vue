<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input class="form-input" v-model="model" ref="input" />
</template>

<style scoped>
/* The component does not define any default styles.
   Instead, parent components style it via CSS variables and :deep(.form-input) rules.
   This ensures full consistency with the dark theme. */
</style>