<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Attendance History</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 border">Date</th>
                                        <th class="px-4 py-2 border">Punch In</th>
                                        <th class="px-4 py-2 border">In Location</th>
                                        <th class="px-4 py-2 border">In IP</th>
                                        <th class="px-4 py-2 border">Punch Out</th>
                                        <th class="px-4 py-2 border">Out Location</th>
                                        <th class="px-4 py-2 border">Out IP</th>
                                        <th class="px-4 py-2 border">Normal Hrs</th>
                                        <th class="px-4 py-2 border">OT Hrs</th>
                                        <th class="px-4 py-2 border">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="att in attendances.data" :key="att.id">
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
                                        <td class="border px-4 py-2 text-center">
                                            {{ (att.normal_hours || 0).toFixed(2) }}
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            {{ (att.overtime_hours || 0).toFixed(2) }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            <span :class="statusClass(att.status)"
                                                class="px-2 py-1 rounded text-xs font-semibold">
                                                {{ att.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="attendances.data?.length === 0">
                                        <td colspan="10" class="text-center py-4 text-gray-500">
                                            No attendance records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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

defineProps({
    attendances: Object
});

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
        'half-day': 'bg-orange-100 text-orange-800',
        'on_leave': 'bg-blue-100 text-blue-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>