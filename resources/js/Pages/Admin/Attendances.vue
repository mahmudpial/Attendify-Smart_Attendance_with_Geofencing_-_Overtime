<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Attendances</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Filters -->
                        <form @submit.prevent="filter">
                            <div class="flex flex-wrap gap-4 mb-6">
                                <select v-model="filters.user_id" class="rounded border-gray-300">
                                    <option value="">All Users</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}
                                    </option>
                                </select>
                                <input type="date" v-model="filters.start_date" placeholder="Start Date"
                                    class="rounded border-gray-300">
                                <input type="date" v-model="filters.end_date" placeholder="End Date"
                                    class="rounded border-gray-300">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                                <button type="button" @click="resetFilters"
                                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</button>
                            </div>
                        </form>

                        <!-- Attendance Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 border">Employee</th>
                                        <th class="px-4 py-2 border">Date</th>
                                        <th class="px-4 py-2 border">Punch In</th>
                                        <th class="px-4 py-2 border">In Location</th>
                                        <th class="px-4 py-2 border">In IP</th>
                                        <th class="px-4 py-2 border">Punch Out</th>
                                        <th class="px-4 py-2 border">Out Location</th>
                                        <th class="px-4 py-2 border">Out IP</th>
                                        <th class="px-4 py-2 border">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="att in attendances.data" :key="att.id">
                                        <td class="border px-4 py-2">{{ att.user?.name || 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ formatDate(att.date) }}</td>
                                        <td class="border px-4 py-2">{{ formatTime(att.punch_in) }}</td>
                                        <td class="border px-4 py-2 text-sm">
                                            <span v-if="att.punch_in_lat && att.punch_in_lng">
                                                {{ att.punch_in_lat.toFixed(6) }}, {{ att.punch_in_lng.toFixed(6) }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="border px-4 py-2">{{ att.punch_in_ip || '—' }}</td>
                                        <td class="border px-4 py-2">{{ formatTime(att.punch_out) }}</td>
                                        <td class="border px-4 py-2 text-sm">
                                            <span v-if="att.punch_out_lat && att.punch_out_lng">
                                                {{ att.punch_out_lat.toFixed(6) }}, {{ att.punch_out_lng.toFixed(6) }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="border px-4 py-2">{{ att.punch_out_ip || '—' }}</td>
                                        <td class="border px-4 py-2">
                                            <span :class="statusClass(att.status)"
                                                class="px-2 py-1 rounded text-xs font-semibold">
                                                {{ att.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="attendances.data?.length === 0">
                                        <td colspan="9" class="text-center py-4 text-gray-500">No attendance records
                                            found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination :links="attendances.links" class="mt-4" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    attendances: Object,
    users: Array,
    filters: Object
});

const filters = ref({
    user_id: props.filters?.user_id || '',
    start_date: props.filters?.start_date || '',
    end_date: props.filters?.end_date || ''
});

// Helper methods
const formatDate = (date) => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString();
};

const formatTime = (timestamp) => {
    if (!timestamp) return '—';
    return new Date(timestamp).toLocaleTimeString();
};

const statusClass = (status) => {
    const classes = {
        present: 'bg-green-100 text-green-800',
        absent: 'bg-red-100 text-red-800',
        late: 'bg-yellow-100 text-yellow-800',
        'half-day': 'bg-orange-100 text-orange-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const filter = () => {
    router.get(route('admin.attendances'), filters.value, { preserveState: true });
};

const resetFilters = () => {
    filters.value = { user_id: '', start_date: '', end_date: '' };
    filter();
};
</script>