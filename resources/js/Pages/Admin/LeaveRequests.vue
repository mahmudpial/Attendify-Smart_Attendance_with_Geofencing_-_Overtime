<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Leave Requests</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Status filter -->
                        <div class="mb-6 flex gap-2">
                            <button @click="filterStatus('')"
                                :class="{ 'bg-blue-600 text-white': !statusFilter, 'bg-gray-200': statusFilter }"
                                class="px-4 py-2 rounded">All</button>
                            <button @click="filterStatus('pending')"
                                :class="{ 'bg-yellow-600 text-white': statusFilter === 'pending', 'bg-gray-200': statusFilter !== 'pending' }"
                                class="px-4 py-2 rounded">Pending</button>
                            <button @click="filterStatus('approved')"
                                :class="{ 'bg-green-600 text-white': statusFilter === 'approved', 'bg-gray-200': statusFilter !== 'approved' }"
                                class="px-4 py-2 rounded">Approved</button>
                            <button @click="filterStatus('rejected')"
                                :class="{ 'bg-red-600 text-white': statusFilter === 'rejected', 'bg-gray-200': statusFilter !== 'rejected' }"
                                class="px-4 py-2 rounded">Rejected</button>
                        </div>

                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border">Employee</th>
                                    <th class="px-4 py-2 border">Type</th>
                                    <th class="px-4 py-2 border">Period</th>
                                    <th class="px-4 py-2 border">Days</th>
                                    <th class="px-4 py-2 border">Reason</th>
                                    <th class="px-4 py-2 border">Status</th>
                                    <th class="px-4 py-2 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="req in leaveRequests.data" :key="req.id">
                                    <td class="border px-4 py-2">{{ req.user.name }}</td>
                                    <td class="border px-4 py-2">{{ req.type }}</td>
                                    <td class="border px-4 py-2">{{ req.start_date }} → {{ req.end_date }}</td>
                                    <td class="border px-4 py-2 text-center">{{ req.days_count }}</td>
                                    <td class="border px-4 py-2">{{ req.reason }}</td>
                                    <td class="border px-4 py-2">
                                        <span :class="statusClass(req.status)"
                                            class="px-2 py-1 rounded text-xs font-semibold">
                                            {{ req.status }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <div v-if="req.status === 'pending'">
                                            <button @click="openModal(req, 'approved')"
                                                class="text-green-600 hover:underline mr-2">Approve</button>
                                            <button @click="openModal(req, 'rejected')"
                                                class="text-red-600 hover:underline">Reject</button>
                                        </div>
                                        <span v-else>{{ req.admin_comment || '—' }}</span>
                                    </td>
                                </tr>
                                <tr v-if="leaveRequests.data?.length === 0">
                                    <td colspan="7" class="text-center py-4">No leave requests found.</td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :links="leaveRequests.links" class="mt-4" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for admin comment -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-medium mb-4">{{ modalAction === 'approved' ? 'Approve Leave' : 'Reject Leave' }}
                </h3>
                <form @submit.prevent="submitAction">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Comment (optional)</label>
                        <textarea v-model="adminComment" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Confirm</button>
                    </div>
                </form>
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
    statusFilter: String,
});

const showModal = ref(false);
const selectedRequest = ref(null);
const modalAction = ref('');
const adminComment = ref('');

const filterStatus = (status) => {
    router.get(route('admin.leave.requests'), { status }, { preserveState: true });
};

const openModal = (request, action) => {
    selectedRequest.value = request;
    modalAction.value = action;
    adminComment.value = '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedRequest.value = null;
    adminComment.value = '';
};

const submitAction = () => {
    const status = modalAction.value;
    router.put(route('admin.leave.requests.update', selectedRequest.value.id), {
        status: status,
        admin_comment: adminComment.value,
    }, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
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