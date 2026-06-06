<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">Manage Attendances</h2>
                        <p class="page-sub">View, filter and audit all attendance records</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">
            <!-- Filters Card -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Filters</span>
                    <span class="live-badge">
                        <span class="live-dot"></span>
                        Live
                    </span>
                </div>

                <form @submit.prevent="filter">
                    <div class="filters-grid">
                        <select v-model="filters.user_id" class="filter-input">
                            <option value="">All Users</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                        <input type="date" v-model="filters.start_date" class="filter-input" placeholder="Start Date">
                        <input type="date" v-model="filters.end_date" class="filter-input" placeholder="End Date">
                        <button type="submit" class="btn btn--primary">Apply Filters</button>
                        <button type="button" @click="resetFilters" class="btn btn--secondary">Reset</button>
                    </div>
                </form>
            </div>

            <!-- Attendance Table Card -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Attendance Records</span>
                    <span class="record-count">{{ attendances.total }} total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>In Location</th>
                                <th>In IP</th>
                                <th>Punch Out</th>
                                <th>Out Location</th>
                                <th>Out IP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="att in attendances.data" :key="att.id">
                                <td data-label="Employee">{{ att.user?.name || 'N/A' }}</td>
                                <td data-label="Date">{{ formatDate(att.date) }}</td>
                                <td data-label="Punch In">{{ formatTime(att.punch_in) }}</td>
                                <td data-label="In Location" class="mono">
                                    <span v-if="att.punch_in_lat && att.punch_in_lng">
                                        {{ att.punch_in_lat.toFixed(6) }}, {{ att.punch_in_lng.toFixed(6) }}
                                    </span>
                                    <span v-else class="empty-placeholder">—</span>
                                </td>
                                <td data-label="In IP" class="mono">{{ att.punch_in_ip || '—' }}</td>
                                <td data-label="Punch Out">{{ formatTime(att.punch_out) }}</td>
                                <td data-label="Out Location" class="mono">
                                    <span v-if="att.punch_out_lat && att.punch_out_lng">
                                        {{ att.punch_out_lat.toFixed(6) }}, {{ att.punch_out_lng.toFixed(6) }}
                                    </span>
                                    <span v-else class="empty-placeholder">—</span>
                                </td>
                                <td data-label="Out IP" class="mono">{{ att.punch_out_ip || '—' }}</td>
                                <td data-label="Status">
                                    <span :class="statusClass(att.status)" class="status-badge">
                                        {{ att.status }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="attendances.data?.length === 0">
                                <td colspan="9" class="empty-state">No attendance records found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <Pagination :links="attendances.links" />
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
        present: 'status-present',
        absent: 'status-absent',
        late: 'status-late',
        'half-day': 'status-half-day',
        on_leave: 'status-on-leave'
    };
    return classes[status] || 'status-default';
};

const filter = () => {
    router.get(route('admin.attendances'), filters.value, { preserveState: true });
};

const resetFilters = () => {
    filters.value = { user_id: '', start_date: '', end_date: '' };
    filter();
};
</script>

<style scoped>
/* ===== DESIGN TOKENS ===== */
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
    --primary-dim: rgba(79, 142, 247, 0.15);
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
    background: var(--primary-dim);
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
    gap: 12px;
    align-items: flex-end;
}

.filter-input {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 14px;
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
    min-width: 160px;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.5);
}

.btn {
    padding: 10px 20px;
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

.mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
}

.empty-placeholder {
    color: var(--text-disabled);
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
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

.empty-state {
    text-align: center;
    padding: 48px !important;
    color: var(--text-muted);
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .dash-body {
        padding: 20px;
    }

    .filters-grid {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-input,
    .btn {
        width: 100%;
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

    .data-table thead {
        display: none;
    }

    .data-table tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface);
    }

    .data-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
    }

    .data-table td:last-child {
        border-bottom: none;
    }

    .data-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-muted);
        width: 40%;
        text-transform: uppercase;
        font-size: 11px;
    }

    .mono {
        word-break: break-all;
    }
}
</style>