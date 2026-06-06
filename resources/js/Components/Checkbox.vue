<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },
    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input type="checkbox" :value="value" v-model="proxyChecked" class="checkbox" />
</template>

<style scoped>
.checkbox {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    background-color: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.16);
    accent-color: #4f8ef7;
    cursor: pointer;
}

.checkbox:focus {
    outline: none;
    ring: 2px solid #4f8ef7;
    ring-offset: 2px;
}

.checkbox:checked {
    background-color: #4f8ef7;
    border-color: #4f8ef7;
}
</style>