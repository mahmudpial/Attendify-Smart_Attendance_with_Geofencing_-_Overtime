<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-2">
        <template v-for="(link, index) in links" :key="index">
            <Link v-if="link.url" :href="link.url" class="px-3 py-2 rounded-md text-sm font-medium" :class="{
                'bg-blue-600 text-white': link.active,
                'bg-gray-200 text-gray-700 hover:bg-gray-300': !link.active,
            }" v-html="link.label" />
            <span v-else class="px-3 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-400"
                v-html="link.label" />
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