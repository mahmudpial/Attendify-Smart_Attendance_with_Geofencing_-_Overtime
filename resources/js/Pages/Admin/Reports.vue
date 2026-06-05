<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attendance Reports</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Date Range Filter -->
                        <form @submit.prevent="generateReport" class="mb-6 flex flex-wrap gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium mb-1">Start Date</label>
                                <input type="date" v-model="filters.start_date" class="border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">End Date</label>
                                <input type="date" v-model="filters.end_date" class="border rounded px-3 py-2">
                            </div>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Generate</button>
                            <button type="button" @click="exportReport"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export CSV</button>
                        </form>

                        <!-- Report Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-blue-800">Total Employees</h3>
                                <p class="text-3xl font-bold">{{ totalEmployees }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-green-800">Total Working Days</h3>
                                <p class="text-3xl font-bold">{{ totalWorkingDays }}</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-purple-800">Total Hours Worked</h3>
                                <p class="text-3xl font-bold">{{ totalHours }}</p>
                            </div>
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-orange-800">Total Overtime</h3>
                                <p class="text-3xl font-bold">{{ totalOvertime }} hrs</p>
                            </div>
                        </div>

                        <!-- Detailed Report Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 border">Employee</th>
                                        <th class="px-4 py-2 border">Total Days</th>
                                        <th class="px-4 py-2 border">Normal Hours</th>
                                        <th class="px-4 py-2 border">Overtime Hours</th>
                                        <th class="px-4 py-2 border">Total Hours</th>
                                        <th class="px-4 py-2 border">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(emp, userId) in report" :key="userId">
                                        <td class="border px-4 py-2">{{ emp.user_name }}</td>
                                        <td class="border px-4 py-2 text-center">{{ emp.total_days }}</td>
                                        <td class="border px-4 py-2 text-center">{{ emp.total_normal.toFixed(2) }}</td>
                                        <td class="border px-4 py-2 text-center">{{ emp.total_overtime.toFixed(2) }}
                                        </td>
                                        <td class="border px-4 py-2 text-center">{{ emp.total_hours.toFixed(2) }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            <button @click="toggleDetails(userId)"
                                                class="text-blue-600 hover:underline">
                                                {{ expandedRows[userId] ? 'Hide' : 'Show' }}
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="Object.keys(report).length === 0">
                                        <td colspan="6" class="text-center py-4 text-gray-500">No data for selected date
                                            range.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Expandable details row (outside the main table) -->
                        <div v-for="(emp, userId) in report" :key="'details-' + userId">
                            <div v-if="expandedRows[userId]" class="mt-4 mb-6 p-4 bg-gray-50 rounded border">
                                <h4 class="font-medium mb-2">{{ emp.user_name }} – Daily Breakdown</h4>
                                <table class="w-full text-sm border">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="px-3 py-1 border">Date</th>
                                            <th class="px-3 py-1 border">Punch In</th>
                                            <th class="px-3 py-1 border">Punch Out</th>
                                            <th class="px-3 py-1 border">Normal Hrs</th>
                                            <th class="px-3 py-1 border">OT Hrs</th>
                                            <th class="px-3 py-1 border">Total Hrs</th>
                                            <th class="px-3 py-1 border">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="record in emp.records" :key="record.id">
                                            <td class="border px-3 py-1">{{ formatDate(record.date) }}</td>
                                            <td class="border px-3 py-1">{{ formatTime(record.punch_in) }}</td>
                                            <td class="border px-3 py-1">{{ formatTime(record.punch_out) }}</td>
                                            <td class="border px-3 py-1 text-center">{{ (record.normal_hours ||
                                                0).toFixed(2) }}
                                            </td>
                                            <td class="border px-3 py-1 text-center">{{ (record.overtime_hours ||
                                                0).toFixed(2)
                                            }}</td>
                                            <td class="border px-3 py-1 text-center">
                                                {{ ((record.normal_hours || 0) + (record.overtime_hours ||
                                                    0)).toFixed(2) }}
                                            </td>
                                            <td class="border px-3 py-1">
                                                <span :class="statusClass(record.status)"
                                                    class="px-2 py-0.5 rounded text-xs font-semibold">
                                                    {{ record.status }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="emp.records.length === 0">
                                            <td colspan="7" class="text-center py-2 text-gray-500">No daily records
                                                found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    report: Object,
    startDate: String,
    endDate: String,
    summary: Object, // optional, from controller
});

const filters = ref({
    start_date: props.startDate || '',
    end_date: props.endDate || '',
});

const expandedRows = ref({});

// Computed totals from report data
const totalEmployees = computed(() => Object.keys(props.report).length);

const totalWorkingDays = computed(() => {
    let total = 0;
    for (let emp in props.report) {
        total += props.report[emp].total_days;
    }
    return total;
});

const totalHours = computed(() => {
    let total = 0;
    for (let emp in props.report) {
        total += props.report[emp].total_hours;
    }
    return total.toFixed(1);
});

const totalOvertime = computed(() => {
    let total = 0;
    for (let emp in props.report) {
        total += props.report[emp].total_overtime || 0;
    }
    return total.toFixed(1);
});

function formatDate(date) {
    if (!date) return '-';
    return new Date(date).toLocaleDateString();
}

function formatTime(timestamp) {
    if (!timestamp) return '-';
    return new Date(timestamp).toLocaleTimeString();
}

function toggleDetails(userId) {
    expandedRows.value[userId] = !expandedRows.value[userId];
}

function generateReport() {
    router.get(route('admin.reports'), filters.value, { preserveState: true });
}

function exportReport() {
    router.post(route('admin.reports.export'), filters.value, { forceFormData: true });
}

function statusClass(status) {
    const classes = {
        present: 'bg-green-100 text-green-800',
        absent: 'bg-red-100 text-red-800',
        late: 'bg-yellow-100 text-yellow-800',
        'half-day': 'bg-orange-100 text-orange-800',
        'on_leave': 'bg-blue-100 text-blue-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}
</script>