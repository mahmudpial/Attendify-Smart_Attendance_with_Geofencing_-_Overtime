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
                        <h2 class="page-title">Manage Leave Requests</h2>
                        <p class="page-sub">Review, approve or reject employee leave requests</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Leave Requests</span>
                    <span class="record-count">{{ leaveRequests.total }} total</span>
                </div>

                <!-- Status filter buttons -->
                <div class="filter-buttons">
                    <button @click="filterStatus('')" :class="['filter-btn', !statusFilter && 'filter-btn--active']">
                        All
                    </button>
                    <button @click="filterStatus('pending')"
                        :class="['filter-btn', statusFilter === 'pending' && 'filter-btn--pending']">
                        Pending
                    </button>
                    <button @click="filterStatus('approved')"
                        :class="['filter-btn', statusFilter === 'approved' && 'filter-btn--approved']">
                        Approved
                    </button>
                    <button @click="filterStatus('rejected')"
                        :class="['filter-btn', statusFilter === 'rejected' && 'filter-btn--rejected']">
                        Rejected
                    </button>
                </div>

                <!-- Leave requests table -->
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Period</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions / Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="req in leaveRequests.data" :key="req.id">
                                <td data-label="Employee">{{ req.user.name }}</td>
                                <td data-label="Type">
                                    <span :class="['type-badge', `type-${req.type}`]">{{ req.type }}</span>
                                </td>
                                <td data-label="Period">{{ req.start_date }} → {{ req.end_date }}</td>
                                <td data-label="Days" class="text-center">{{ req.days_count }}</td>
                                <td data-label="Reason">{{ req.reason }}</td>
                                <td data-label="Status">
                                    <span :class="['status-badge', statusClass(req.status)]">
                                        {{ req.status }}
                                    </span>
                                </td>
                                <td data-label="Actions / Comment">
                                    <div v-if="req.status === 'pending'" class="action-buttons">
                                        <button @click="openModal(req, 'approved')"
                                            class="action-btn action-btn--approve">
                                            Approve
                                        </button>
                                        <button @click="openModal(req, 'rejected')"
                                            class="action-btn action-btn--reject">
                                            Reject
                                        </button>
                                    </div>
                                    <span v-else class="admin-comment">{{ req.admin_comment || '—' }}</span>
                                </td>
                            </tr>
                            <tr v-if="leaveRequests.data?.length === 0">
                                <td colspan="7" class="empty-state">No leave requests found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <Pagination :links="leaveRequests.links" />
                </div>
            </div>
        </div>

        <!-- Modal for admin comment (dark themed) -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal-container">
                <h3 class="modal-title">{{ modalAction === 'approved' ? 'Approve Leave' : 'Reject Leave' }}</h3>
                <form @submit.prevent="submitAction">
                    <div class="mb-4">
                        <label class="modal-label">Comment (optional)</label>
                        <textarea v-model="adminComment" rows="3" class="modal-textarea"></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" @click="closeModal" class="modal-btn modal-btn--cancel">Cancel</button>
                        <button type="submit" class="modal-btn modal-btn--confirm">Confirm</button>
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

// Safe route builder (bypass Ziggy) – unchanged
const safeRoute = (name, id = null) => {
    if (name === 'admin.leave.requests.update' && id) {
        return `/admin/leave-requests/${id}`;
    }
    if (name === 'admin.leave.requests') {
        return '/admin/leave-requests';
    }
    return `/${name.replace(/\./g, '-')}`;
};

const showModal = ref(false);
const selectedRequest = ref(null);
const modalAction = ref('');
const adminComment = ref('');

const filterStatus = (status) => {
    router.get(safeRoute('admin.leave.requests'), { status }, { preserveState: true });
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
    const url = safeRoute('admin.leave.requests.update', selectedRequest.value.id);
    router.put(url, {
        status: status,
        admin_comment: adminComment.value,
    }, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
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

/* ===== CARD ===== */
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

.record-count {
    font-size: 12px;
    color: var(--text-muted);
    background: rgba(0, 0, 0, 0.3);
    padding: 4px 10px;
    border-radius: 999px;
}

/* ===== FILTER BUTTONS ===== */
.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 24px;
}

.filter-btn {
    padding: 8px 20px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn:hover {
    background: var(--surface-hover);
    color: var(--text);
}

.filter-btn--active {
    background: var(--primary);
    color: #0a0f1e;
    border-color: var(--primary);
}

.filter-btn--pending {
    background: rgba(251, 191, 36, 0.15);
    color: var(--warning);
    border-color: rgba(251, 191, 36, 0.4);
}

.filter-btn--approved {
    background: rgba(52, 211, 153, 0.15);
    color: var(--success);
    border-color: rgba(52, 211, 153, 0.4);
}

.filter-btn--rejected {
    background: rgba(248, 113, 113, 0.15);
    color: var(--danger);
    border-color: rgba(248, 113, 113, 0.4);
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

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.action-btn--approve {
    background: rgba(52, 211, 153, 0.15);
    color: var(--success);
    border: 1px solid rgba(52, 211, 153, 0.3);
}

.action-btn--approve:hover {
    background: var(--success);
    color: #0a0f1e;
}

.action-btn--reject {
    background: rgba(248, 113, 113, 0.15);
    color: var(--danger);
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.action-btn--reject:hover {
    background: var(--danger);
    color: #0a0f1e;
}

.admin-comment {
    color: var(--text-muted);
    font-size: 12px;
    display: inline-block;
    max-width: 180px;
    word-break: break-word;
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

/* ===== MODAL (Dark Theme) ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

.modal-container {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    width: 90%;
    max-width: 440px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 20px;
}

.modal-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.modal-textarea {
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
    font-size: 14px;
    resize: vertical;
}

.modal-textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
}

.modal-btn {
    padding: 8px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.modal-btn--cancel {
    background: rgba(255, 255, 255, 0.08);
    color: var(--text);
    border: 1px solid var(--border);
}

.modal-btn--cancel:hover {
    background: rgba(255, 255, 255, 0.12);
}

.modal-btn--confirm {
    background: var(--primary);
    color: #0a0f1e;
}

.modal-btn--confirm:hover {
    background: #3b7ae6;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .dash-body {
        padding: 20px;
    }
}

@media (max-width: 768px) {
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
        width: 35%;
        text-transform: uppercase;
        font-size: 11px;
    }

    .action-buttons {
        flex-direction: column;
        width: 100%;
    }

    .admin-comment {
        text-align: right;
        word-break: break-word;
    }
}
</style>