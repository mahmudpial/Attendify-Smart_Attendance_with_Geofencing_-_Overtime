<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12v3a4 4 0 01-4 4H7a4 4 0 01-4-4v-3" />
                            <polyline points="8 8 12 4 16 8" />
                            <line x1="12" y1="4" x2="12" y2="16" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">Attendance Reports</h2>
                        <p class="page-sub">Summarised and detailed attendance data</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">
            <!-- Filter Card -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Date Range</span>
                    <span class="live-badge">
                        <span class="live-dot"></span>
                        Live
                    </span>
                </div>

                <form @submit.prevent="generateReport" class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Start Date</label>
                        <input type="date" v-model="filters.start_date" class="filter-input">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">End Date</label>
                        <input type="date" v-model="filters.end_date" class="filter-input">
                    </div>
                    <button type="submit" class="btn btn--primary">Generate Report</button>
                    <button type="button" @click="exportReport" class="btn btn--secondary">Export CSV</button>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-icon stat-card-icon--blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 00-3-3.87" />
                            <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value">{{ totalEmployees }}</div>
                        <div class="stat-card-label">Total Employees</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon stat-card-icon--green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value">{{ totalWorkingDays }}</div>
                        <div class="stat-card-label">Working Days</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon stat-card-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value">{{ totalHours }}<span class="stat-card-unit">h</span></div>
                        <div class="stat-card-label">Total Hours</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon stat-card-icon--amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value">{{ totalOvertime }}<span class="stat-card-unit">h</span></div>
                        <div class="stat-card-label">Overtime</div>
                    </div>
                </div>
            </div>

            <!-- Report Table Card -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Employee Summary</span>
                    <span class="record-count">{{ Object.keys(report).length }} employees</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Days</th>
                                <th>Normal (h)</th>
                                <th>Overtime (h)</th>
                                <th>Total (h)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(emp, userId) in report" :key="userId">
                                <td data-label="Employee">{{ emp.user_name }}</td>
                                <td data-label="Days" class="text-center">{{ emp.total_days }}</td>
                                <td data-label="Normal" class="text-center">{{ formatNumber(emp.total_normal) }}</td>
                                <td data-label="Overtime" class="text-center">{{ formatNumber(emp.total_overtime) }}
                                </td>
                                <td data-label="Total" class="text-center">{{ formatNumber(emp.total_hours) }}</td>
                                <td data-label="Details" class="text-center">
                                    <button @click="toggleDetails(userId)" class="details-btn">
                                        {{ expandedRows[userId] ? 'Hide' : 'Show' }}
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="Object.keys(report).length === 0">
                                <td colspan="6" class="empty-state">No data for selected date range.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Expandable daily breakdown cards -->
                <div v-for="(emp, userId) in report" :key="'details-' + userId" class="mt-4">
                    <div v-if="expandedRows[userId]" class="details-card">
                        <div class="details-header">
                            <span class="details-title">{{ emp.user_name }} – Daily Breakdown</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Punch In</th>
                                        <th>Punch Out</th>
                                        <th>Normal (h)</th>
                                        <th>OT (h)</th>
                                        <th>Total (h)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="record in emp.records" :key="record.id">
                                        <td data-label="Date">{{ formatDate(record.date) }}</td>
                                        <td data-label="Punch In">{{ formatTime(record.punch_in) }}</td>
                                        <td data-label="Punch Out">{{ formatTime(record.punch_out) }}</td>
                                        <td data-label="Normal" class="text-center">{{ formatNumber(record.normal_hours)
                                            }}</td>
                                        <td data-label="OT" class="text-center">{{ formatNumber(record.overtime_hours)
                                            }}</td>
                                        <td data-label="Total" class="text-center">{{ formatNumber((record.normal_hours
                                            || 0) +
                                            (record.overtime_hours || 0)) }}</td>
                                        <td data-label="Status">
                                            <span :class="['status-badge', statusClass(record.status)]">
                                                {{ record.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="emp.records.length === 0">
                                        <td colspan="7" class="empty-state">No daily records found.</td>
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
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    report: Object,
    startDate: String,
    endDate: String,
    summary: Object,
});

const filters = ref({
    start_date: props.startDate || '',
    end_date: props.endDate || '',
});

const expandedRows = ref({});

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

// Safe number formatter
const formatNumber = (value) => {
    if (value === undefined || value === null) return '0.00';
    const num = parseFloat(value);
    return isNaN(num) ? '0.00' : num.toFixed(2);
};

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

const exportReport = () => {
    router.post('/admin/reports/export', filters.value, { forceFormData: true });
};
function statusClass(status) {
    const classes = {
        present: 'status-present',
        absent: 'status-absent',
        late: 'status-late',
        'half-day': 'status-half-day',
        on_leave: 'status-on-leave',
    };
    return classes[status] || 'status-default';
}
</script>

<style scoped>
/* ===== DESIGN TOKENS (dark theme) ===== */
.dash-body {
    --bg-dark: #0a0f1e;
    --surface: rgba(255, 255, 255, 0.045);
    --surface-hover: rgba(255, 255, 255, 0.075);
    --border: rgba(255, 255, 255, 0.08);
    --border-hover: rgba(255, 255, 255, 0.16);
    --text: #f0f4ff;
    --text-muted: #8b97b8;
    --text-disabled: #545e7a;
    --primary: #4f8ef7;
    --success: #34d399;
    --danger: #f87171;
    --warning: #fbbf24;
    --info: #a78bfa;
    --r: 12px;
    --font: 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif;

    font-family: var(--font);
    background: var(--bg-dark);
    min-height: 100%;
    padding: 24px 32px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ===== PAGE HEADER ===== */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(79, 142, 247, 0.12);
    border: 1px solid rgba(79, 142, 247, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.page-header-icon svg {
    width: 24px;
    height: 24px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
}

.page-sub {
    font-size: 14px;
    color: var(--text-muted);
    margin-top: 2px;
}

/* ===== CARDS ===== */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 24px;
    transition: border-color 0.2s;
}

.card:hover {
    border-color: var(--border-hover);
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.card-label {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
}

.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 999px;
    background: rgba(52, 211, 153, 0.1);
    border: 1px solid rgba(52, 211, 153, 0.25);
    font-size: 11px;
    font-weight: 700;
    color: var(--success);
}

.live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--success);
    box-shadow: 0 0 6px var(--success);
    animation: blink 2s ease-in-out infinite;
}

@keyframes blink {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.3;
    }
}

.record-count {
    font-size: 12px;
    color: var(--text-muted);
    background: rgba(0, 0, 0, 0.3);
    padding: 4px 10px;
    border-radius: 999px;
}

/* ===== FILTERS ===== */
.filters-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-input {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 14px;
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
    min-width: 180px;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.5);
}

.btn {
    padding: 10px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn--primary {
    background: var(--primary);
    color: #0a0f1e;
}

.btn--primary:hover {
    background: #3b7ae6;
    transform: translateY(-1px);
}

.btn--secondary {
    background: rgba(255, 255, 255, 0.08);
    color: var(--text);
    border: 1px solid var(--border);
}

.btn--secondary:hover {
    background: rgba(255, 255, 255, 0.12);
}

/* ===== STATS GRID ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 20px;
    transition: all 0.2s;
}

.stat-card:hover {
    background: var(--surface-hover);
    border-color: var(--border-hover);
}

.stat-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-card-icon svg {
    width: 24px;
    height: 24px;
}

.stat-card-icon--blue {
    background: rgba(79, 142, 247, 0.12);
    color: var(--primary);
    border: 1px solid rgba(79, 142, 247, 0.25);
}

.stat-card-icon--green {
    background: rgba(52, 211, 153, 0.12);
    color: var(--success);
    border: 1px solid rgba(52, 211, 153, 0.25);
}

.stat-card-icon--purple {
    background: rgba(167, 139, 250, 0.12);
    color: var(--info);
    border: 1px solid rgba(167, 139, 250, 0.25);
}

.stat-card-icon--amber {
    background: rgba(251, 191, 36, 0.12);
    color: var(--warning);
    border: 1px solid rgba(251, 191, 36, 0.25);
}

.stat-card-body {
    flex: 1;
}

.stat-card-value {
    font-size: 32px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.5px;
    line-height: 1;
}

.stat-card-unit {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    margin-left: 2px;
}

.stat-card-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-top: 4px;
}

/* ===== DATA TABLE ===== */
.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.data-table th {
    text-align: left;
    padding: 12px 16px;
    background: rgba(0, 0, 0, 0.2);
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border);
}

.data-table td {
    padding: 14px 16px;
    color: var(--text);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.data-table tr:hover td {
    background: var(--surface-hover);
}

.text-center {
    text-align: center;
}

.details-btn {
    background: none;
    border: none;
    color: var(--primary);
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s;
}

.details-btn:hover {
    color: #3b7ae6;
}

.empty-state {
    text-align: center;
    padding: 48px !important;
    color: var(--text-muted);
}

/* ===== DETAILS SECTION ===== */
.details-card {
    margin-top: 16px;
    padding: 20px;
    background: rgba(0, 0, 0, 0.25);
    border-radius: var(--r);
    border: 1px solid var(--border);
}

.details-header {
    margin-bottom: 16px;
}

.details-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
}

.details-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.details-table th {
    text-align: left;
    padding: 10px 12px;
    background: rgba(0, 0, 0, 0.2);
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border);
}

.details-table td {
    padding: 10px 12px;
    color: var(--text);
    border-bottom: 1px solid var(--border);
}

.details-table tr:hover td {
    background: var(--surface-hover);
}

/* Status badges (dark theme) */
.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.status-present {
    background: rgba(52, 211, 153, 0.12);
    color: var(--success);
    border: 1px solid rgba(52, 211, 153, 0.3);
}

.status-absent {
    background: rgba(248, 113, 113, 0.12);
    color: var(--danger);
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.status-late {
    background: rgba(251, 191, 36, 0.12);
    color: var(--warning);
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-half-day {
    background: rgba(251, 191, 36, 0.12);
    color: var(--warning);
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-on-leave {
    background: rgba(167, 139, 250, 0.12);
    color: var(--info);
    border: 1px solid rgba(167, 139, 250, 0.3);
}

.status-default {
    background: rgba(255, 255, 255, 0.06);
    color: var(--text-muted);
    border: 1px solid var(--border);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .dash-body {
        padding: 20px;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .dash-body {
        padding: 16px;
        gap: 16px;
    }

    .card {
        padding: 18px;
    }

    .page-header-icon {
        width: 40px;
        height: 40px;
    }

    .page-header-icon svg {
        width: 20px;
        height: 20px;
    }

    .page-title {
        font-size: 20px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .filters-grid {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-input,
    .btn {
        width: 100%;
    }

    .data-table thead,
    .details-table thead {
        display: none;
    }

    .data-table tr,
    .details-table tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface);
    }

    .data-table td,
    .details-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
    }

    .data-table td:last-child,
    .details-table td:last-child {
        border-bottom: none;
    }

    .data-table td::before,
    .details-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-muted);
        width: 40%;
        text-transform: uppercase;
        font-size: 10px;
    }

    .text-center {
        text-align: left;
    }
}
</style>