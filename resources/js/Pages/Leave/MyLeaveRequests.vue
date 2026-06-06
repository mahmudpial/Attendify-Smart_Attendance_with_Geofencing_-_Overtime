<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">My Leave Requests</h2>
                        <p class="page-sub">Manage and track your leave requests</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">
            <!-- Leave Balances -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Leave Balances</span>
                    <span class="live-badge">
                        <span class="live-dot"></span>
                        Available
                    </span>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-icon stat-card-icon--blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                            </svg>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-card-value">{{ balances.annual || 0 }}<span
                                    class="stat-card-unit">days</span>
                            </div>
                            <div class="stat-card-label">Annual Leave</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-icon stat-card-icon--green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-card-value">{{ balances.sick || 0 }}<span
                                    class="stat-card-unit">days</span></div>
                            <div class="stat-card-label">Sick Leave</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-icon stat-card-icon--amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-card-value">{{ balances.casual || 0 }}<span
                                    class="stat-card-unit">days</span>
                            </div>
                            <div class="stat-card-label">Casual Leave</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Leave Request Form -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Request New Leave</span>
                </div>

                <form @submit.prevent="submitLeave" class="leave-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Leave Type</label>
                            <select v-model="form.type" required class="form-select">
                                <option value="annual">Annual</option>
                                <option value="sick">Sick</option>
                                <option value="casual">Casual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" v-model="form.start_date" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" v-model="form.end_date" required class="form-input">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Reason</label>
                            <textarea v-model="form.reason" rows="3" required class="form-textarea"></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">Submit Request</button>
                    </div>
                </form>
            </div>

            <!-- Leave Requests History -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">My Leave History</span>
                    <span class="record-count">{{ leaveRequests.total }} total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Period</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Admin Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="req in leaveRequests.data" :key="req.id">
                                <td data-label="Type">
                                    <span :class="['type-badge', `type-${req.type}`]">{{ req.type }}</span>
                                </td>
                                <td data-label="Period">{{ req.start_date }} → {{ req.end_date }}</td>
                                <td data-label="Days" class="text-center">{{ req.days_count || 0 }}</td>
                                <td data-label="Reason">{{ req.reason }}</td>
                                <td data-label="Status">
                                    <span :class="['status-badge', statusClass(req.status)]">
                                        {{ req.status }}
                                    </span>
                                </td>
                                <td data-label="Admin Comment">{{ req.admin_comment || '—' }}</td>
                            </tr>
                            <tr v-if="leaveRequests.data?.length === 0">
                                <td colspan="6" class="empty-state">No leave requests found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <Pagination :links="leaveRequests.links" />
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
    const url = '/leave-requests';
    router.post(url, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            form.value = { type: 'annual', start_date: '', end_date: '', reason: '' };
        },
    });
};

const statusClass = (status) => {
    return {
        pending: 'status-pending',
        approved: 'status-approved',
        rejected: 'status-rejected',
    }[status];
};
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
    --primary-dim: rgba(79, 142, 247, 0.15);
    --danger: #f87171;
    --success: #34d399;
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
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--r);
    padding: 20px;
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
    margin-left: 4px;
}

.stat-card-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-top: 4px;
}

/* ===== FORM ===== */
.leave-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.full-width {
    grid-column: span 2;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 14px;
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.5);
}

.form-textarea {
    resize: vertical;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
}

.submit-btn {
    background: var(--primary);
    border: none;
    border-radius: 8px;
    padding: 10px 24px;
    font-size: 14px;
    font-weight: 700;
    color: #0a0f1e;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-btn:hover {
    background: #3b7ae6;
    transform: translateY(-1px);
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

/* Type badges */
.type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.type-annual {
    background: rgba(79, 142, 247, 0.12);
    color: var(--primary);
    border: 1px solid rgba(79, 142, 247, 0.3);
}

.type-sick {
    background: rgba(167, 139, 250, 0.12);
    color: var(--info);
    border: 1px solid rgba(167, 139, 250, 0.3);
}

.type-casual {
    background: rgba(52, 211, 153, 0.12);
    color: var(--success);
    border: 1px solid rgba(52, 211, 153, 0.3);
}

/* Status badges */
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.status-pending {
    background: rgba(251, 191, 36, 0.12);
    color: var(--warning);
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-approved {
    background: rgba(52, 211, 153, 0.12);
    color: var(--success);
    border: 1px solid rgba(52, 211, 153, 0.3);
}

.status-rejected {
    background: rgba(248, 113, 113, 0.12);
    color: var(--danger);
    border: 1px solid rgba(248, 113, 113, 0.3);
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

    .form-grid {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: span 1;
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
        width: 35%;
        text-transform: uppercase;
        font-size: 11px;
    }
}
</style>