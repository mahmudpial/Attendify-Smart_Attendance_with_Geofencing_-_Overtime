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
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">My Attendance History</h2>
                        <p class="page-sub">All your clock records in one place</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="hist-body">

            <!-- ══ TABLE CARD ═══════════════════════════════ -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Records</span>
                    <span class="record-count" v-if="attendances.data?.length">
                        {{ attendances.data.length }} entries
                    </span>
                </div>

                <!-- Table wrapper -->
                <div class="table-wrap">
                    <table class="att-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>In Location</th>
                                <th>In IP</th>
                                <th>Punch Out</th>
                                <th>Out Location</th>
                                <th>Out IP</th>
                                <th class="text-center">Normal h</th>
                                <th class="text-center">OT h</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="att in attendances.data" :key="att.id" class="att-row">
                                <td class="cell-date">{{ formatDate(att.date) }}</td>
                                <td class="cell-time cell-time--in">{{ formatTime(att.punch_in) }}</td>
                                <td class="cell-coord">
                                    <span v-if="att.punch_in_lat && att.punch_in_lng">
                                        {{ att.punch_in_lat.toFixed(6) }}, {{ att.punch_in_lng.toFixed(6) }}
                                    </span>
                                    <span v-else class="cell-empty">—</span>
                                </td>
                                <td class="cell-ip">{{ att.punch_in_ip || '—' }}</td>
                                <td class="cell-time cell-time--out">{{ formatTime(att.punch_out) }}</td>
                                <td class="cell-coord">
                                    <span v-if="att.punch_out_lat && att.punch_out_lng">
                                        {{ att.punch_out_lat.toFixed(6) }}, {{ att.punch_out_lng.toFixed(6) }}
                                    </span>
                                    <span v-else class="cell-empty">—</span>
                                </td>
                                <td class="cell-ip">{{ att.punch_out_ip || '—' }}</td>
                                <td class="text-center cell-hours">{{ formatNumber(att.normal_hours) }}</td>
                                <td class="text-center cell-ot">{{ formatNumber(att.overtime_hours) }}</td>
                                <td class="text-center">
                                    <span :class="['status-badge', 'status-badge--' + att.status]">
                                        {{ att.status }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Empty state -->
                            <tr v-if="attendances.data?.length === 0">
                                <td colspan="10" class="empty-state">
                                    <div class="empty-state-inner">
                                        <div class="empty-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                                <polyline points="14 2 14 8 20 8" />
                                            </svg>
                                        </div>
                                        <div class="empty-title">No attendance records found</div>
                                        <div class="empty-sub">Start by punching in from your dashboard</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrap">
                    <Pagination :links="attendances.links" class="mt-4" />
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

const formatNumber = (value) => {
    if (value === undefined || value === null) return '0.00';
    const num = parseFloat(value);
    return isNaN(num) ? '0.00' : num.toFixed(2);
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

<style scoped>
/* ════════════════════════════════
   TOKENS  (matches Welcome.vue)
════════════════════════════════ */
* {
    box-sizing: border-box;
}

.hist-body {
    --bg: #0a0f1e;
    --bg2: #0f1629;
    --surface: rgba(255, 255, 255, 0.045);
    --surface-h: rgba(255, 255, 255, 0.075);
    --border: rgba(255, 255, 255, 0.08);
    --border-h: rgba(255, 255, 255, 0.16);
    --text: #f0f4ff;
    --text-2: #8b97b8;
    --text-3: #545e7a;
    --blue: #4f8ef7;
    --blue-dim: rgba(79, 142, 247, 0.15);
    --emerald: #34d399;
    --amber: #fbbf24;
    --violet: #a78bfa;
    --rose: #f87171;
    --cyan: #22d3ee;
    --r: 12px;
    --font: 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif;

    font-family: var(--font);
    background: var(--bg);
    min-height: 100%;
    padding: 32px;
    color: var(--text);
}

/* ════════════════════════════════
   PAGE HEADER  (slot)
════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.page-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--blue-dim);
    border: 1px solid rgba(79, 142, 247, .3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--blue);
}

.page-header-icon svg {
    width: 18px;
    height: 18px;
}

.page-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -.2px;
}

.page-sub {
    font-size: 12px;
    color: var(--text-3);
    margin-top: 1px;
}

/* ════════════════════════════════
   CARD
════════════════════════════════ */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    transition: border-color .2s;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px 0;
    margin-bottom: 16px;
}

.card-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-3);
}

.record-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-3);
    padding: 3px 10px;
    border-radius: 999px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid var(--border);
}

/* ════════════════════════════════
   TABLE
════════════════════════════════ */
.table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.att-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

/* Head */
.att-table thead tr {
    border-bottom: 1px solid var(--border);
}

.att-table th {
    padding: 11px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--text-3);
    white-space: nowrap;
    background: rgba(0, 0, 0, .15);
}

.att-table th.text-center {
    text-align: center;
}

/* Body rows */
.att-row {
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}

.att-row:last-child {
    border-bottom: none;
}

.att-row:hover {
    background: var(--surface-h);
}

.att-table td {
    padding: 13px 16px;
    color: var(--text-2);
    white-space: nowrap;
    vertical-align: middle;
}

/* Cell types */
.cell-date {
    font-weight: 600;
    color: var(--text);
    font-size: 13px;
}

.cell-time {
    font-weight: 600;
    font-size: 13px;
}

.cell-time--in {
    color: var(--emerald);
}

.cell-time--out {
    color: var(--rose);
}

.cell-coord {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    color: var(--text-3);
    max-width: 160px;
}

.cell-ip {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    color: var(--text-3);
}

.cell-hours {
    font-weight: 700;
    color: var(--blue);
}

.cell-ot {
    font-weight: 700;
    color: var(--amber);
}

.cell-empty {
    color: var(--text-3);
}

.text-center {
    text-align: center;
}

/* ════════════════════════════════
   STATUS BADGES
════════════════════════════════ */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
    white-space: nowrap;
}

.status-badge--present {
    background: rgba(52, 211, 153, .1);
    color: var(--emerald);
    border: 1px solid rgba(52, 211, 153, .25);
}

.status-badge--absent {
    background: rgba(248, 113, 113, .1);
    color: var(--rose);
    border: 1px solid rgba(248, 113, 113, .25);
}

.status-badge--late {
    background: rgba(251, 191, 36, .1);
    color: var(--amber);
    border: 1px solid rgba(251, 191, 36, .25);
}

.status-badge--half-day {
    background: rgba(251, 191, 36, .1);
    color: var(--amber);
    border: 1px solid rgba(251, 191, 36, .25);
}

.status-badge--on_leave {
    background: rgba(79, 142, 247, .1);
    color: var(--blue);
    border: 1px solid rgba(79, 142, 247, .25);
}

/* ════════════════════════════════
   EMPTY STATE
════════════════════════════════ */
.empty-state {
    padding: 0 !important;
}

.empty-state-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 72px 24px;
    gap: 12px;
}

.empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--surface);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-3);
}

.empty-icon svg {
    width: 28px;
    height: 28px;
}

.empty-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-2);
}

.empty-sub {
    font-size: 13px;
    color: var(--text-3);
}

/* ════════════════════════════════
   PAGINATION
════════════════════════════════ */
.pagination-wrap {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
}

/* ════════════════════════════════
   RESPONSIVE
════════════════════════════════ */
@media (max-width: 640px) {
    .hist-body {
        padding: 20px 16px;
    }
}
</style>