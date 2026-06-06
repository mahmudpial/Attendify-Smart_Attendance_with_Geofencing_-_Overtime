<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Leave Requests</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Leave Balances -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Leave Balances</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-blue-100 p-4 rounded">
                                <p class="font-semibold">Annual</p>
                                <p class="text-2xl">{{ balances.annual || 0 }} days</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded">
                                <p class="font-semibold">Sick</p>
                                <p class="text-2xl">{{ balances.sick || 0 }} days</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded">
                                <p class="font-semibold">Casual</p>
                                <p class="text-2xl">{{ balances.casual || 0 }} days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Leave Request Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Request New Leave</h3>
                        <form @submit.prevent="submitLeave">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Leave Type</label>
                                    <select v-model="form.type" required class="w-full border rounded px-3 py-2">
                                        <option value="annual">Annual</option>
                                        <option value="sick">Sick</option>
                                        <option value="casual">Casual</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Start Date</label>
                                    <input type="date" v-model="form.start_date" required
                                        class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">End Date</label>
                                    <input type="date" v-model="form.end_date" required
                                        class="w-full border rounded px-3 py-2">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-1">Reason</label>
                                    <textarea v-model="form.reason" rows="3" required
                                        class="w-full border rounded px-3 py-2"></textarea>
                                </div>
                            </div>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit
                                Request</button>
                        </form>
                    </div>
                </div>

                <!-- Leave Requests History -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">My Leave History</h3>
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border">Type</th>
                                    <th class="px-4 py-2 border">Period</th>
                                    <th class="px-4 py-2 border">Days</th>
                                    <th class="px-4 py-2 border">Reason</th>
                                    <th class="px-4 py-2 border">Status</th>
                                    <th class="px-4 py-2 border">Admin Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="req in leaveRequests.data" :key="req.id">
                                    <td class="border px-4 py-2">{{ req.type }}</td>
                                    <td class="border px-4 py-2">{{ req.start_date }} → {{ req.end_date }}</td>
                                    <td class="border px-4 py-2 text-center">{{ req.days_count || 0 }}</td>
                                    <td class="border px-4 py-2">{{ req.reason }}</td>
                                    <td class="border px-4 py-2">
                                        <span :class="statusClass(req.status)"
                                            class="px-2 py-1 rounded text-xs font-semibold">
                                            {{ req.status }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">{{ req.admin_comment || '—' }}</td>
                                </tr>
                                <tr v-if="leaveRequests.data?.length === 0">
                                    <td colspan="6" class="text-center py-4 text-gray-500">No leave requests found.</td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :links="leaveRequests.links" class="mt-4" />
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
    leaveRequests: Object,
    balances: Object,
});

const form = ref({
    type: 'annual',
    start_date: '',
    end_date: '',
    reason: '',
});

const submitLeave = () => {
    // Hardcoded URL – no route helper
    const url = '/leave-requests';
    console.log('Submitting leave to:', url);
    router.post(url, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            form.value = { type: 'annual', start_date: '', end_date: '', reason: '' };
        },
    });
};

const statusClass = (status) => {
    return {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
    }[status];
};
</script>