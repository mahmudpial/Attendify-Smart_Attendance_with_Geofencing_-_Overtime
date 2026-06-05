<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Quick Actions -->
                        <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <Link :href="route('admin.attendances')"
                                class="bg-blue-500 text-white p-4 rounded text-center hover:bg-blue-600">
                                Manage Attendances
                            </Link>
                            <Link :href="route('admin.reports')"
                                class="bg-green-500 text-white p-4 rounded text-center hover:bg-green-600">
                                View Reports
                            </Link>
                            <Link :href="route('admin.users.index')"
                                class="bg-purple-500 text-white p-4 rounded text-center hover:bg-purple-600">
                                Manage Users
                            </Link>

                            <Link :href="route('admin.leave.requests')"
                                class="bg-yellow-500 text-white p-4 rounded text-center hover:bg-yellow-600">
                                Manage Leave Requests
                            </Link>
                        </div>

                        <!-- Recent Attendances Table -->
                        <h3 class="text-lg font-medium mb-4">Recent Attendances</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 border">Employee</th>
                                        <th class="px-4 py-2 border">Date</th>
                                        <th class="px-4 py-2 border">Punch In</th>
                                        <th class="px-4 py-2 border">Punch Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="att in recentAttendances" :key="att.id">
                                        <td class="border px-4 py-2">{{ att.user?.name || 'Unknown' }}</td>
                                        <td class="border px-4 py-2">{{ formatDate(att.date) }}</td>
                                        <td class="border px-4 py-2">{{ formatTime(att.punch_in) }}</td>
                                        <td class="border px-4 py-2">{{ formatTime(att.punch_out) }}</td>
                                    </tr>
                                    <tr v-if="recentAttendances.length === 0">
                                        <td colspan="4" class="text-center py-4 text-gray-500">No attendance records
                                            yet.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    recentAttendances: Array,
});

function formatDate(date) {
    if (!date) return '-';
    return new Date(date).toLocaleDateString();
}

function formatTime(timestamp) {
    if (!timestamp) return '-';
    return new Date(timestamp).toLocaleTimeString();
}
</script>