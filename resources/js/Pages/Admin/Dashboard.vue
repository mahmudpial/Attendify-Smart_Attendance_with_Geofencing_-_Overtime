<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">Admin Dashboard</h2>
                        <p class="page-sub">Overview & quick actions</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">
            <!-- Quick Actions Grid -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Quick Actions</span>
                </div>
                <div class="actions-grid">
                    <Link :href="route('admin.attendances')" class="action-card action-card--blue">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                        <span class="action-title">Manage Attendances</span>
                        <span class="action-arrow">→</span>
                    </Link>

                    <Link :href="route('admin.reports')" class="action-card action-card--green">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12v3a4 4 0 01-4 4H7a4 4 0 01-4-4v-3" />
                            <polyline points="8 8 12 4 16 8" />
                            <line x1="12" y1="4" x2="12" y2="16" />
                        </svg>
                        <span class="action-title">View Reports</span>
                        <span class="action-arrow">→</span>
                    </Link>

                    <Link :href="route('admin.users.index')" class="action-card action-card--purple">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 00-3-3.87" />
                            <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                        <span class="action-title">Manage Users</span>
                        <span class="action-arrow">→</span>
                    </Link>

                    <Link :href="route('admin.leave.requests')" class="action-card action-card--amber">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span class="action-title">Manage Leave Requests</span>
                        <span class="action-arrow">→</span>
                    </Link>
                </div>
            </div>

            <!-- Recent Attendances Table -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Recent Attendances</span>
                    <span class="live-badge">
                        <span class="live-dot"></span>
                        Live
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="att in recentAttendances" :key="att.id">
                                <td data-label="Employee">{{ att.user?.name || 'Unknown' }}</td>
                                <td data-label="Date">{{ formatDate(att.date) }}</td>
                                <td data-label="Punch In">{{ formatTime(att.punch_in) }}</td>
                                <td data-label="Punch Out">{{ formatTime(att.punch_out) }}</td>
                            </tr>
                            <tr v-if="recentAttendances.length === 0">
                                <td colspan="4" class="empty-state">No attendance records yet.</td>
                            </tr>
                        </tbody>
                    </table>
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

<style scoped>
/* Design tokens – matches the dark theme */
.dash-body {
    --bg-dark: #0a0f1e;
    --surface: rgba(255, 255, 255, 0.045);
    --surface-hover: rgba(255, 255, 255, 0.075);
    --border: rgba(255, 255, 255, 0.08);
    --border-hover: rgba(255, 255, 255, 0.16);
    --text: #f0f4ff;
    --text-muted: #8b97b8;
    --text-disabled: #545e7a;
    --blue: #4f8ef7;
    --green: #34d399;
    --purple: #a78bfa;
    --amber: #fbbf24;
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

/* Page Header (slot) */
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
    color: var(--blue);
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

/* Card */
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
    color: var(--green);
}

.live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--green);
    box-shadow: 0 0 6px var(--green);
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

/* Actions Grid */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 24px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid var(--border);
    background: rgba(0, 0, 0, 0.2);
}

.action-card:hover {
    transform: translateY(-2px);
    border-color: var(--border-hover);
    background: var(--surface-hover);
}

.action-icon {
    width: 28px;
    height: 28px;
    flex-shrink: 0;
}

.action-title {
    flex: 1;
    font-size: 15px;
    font-weight: 600;
    color: var(--text);
}

.action-arrow {
    font-size: 18px;
    color: var(--text-muted);
    transition: transform 0.2s;
}

.action-card:hover .action-arrow {
    transform: translateX(4px);
    color: var(--text);
}

/* Card variant colors */
.action-card--blue .action-icon {
    color: var(--blue);
}

.action-card--green .action-icon {
    color: var(--green);
}

.action-card--purple .action-icon {
    color: var(--purple);
}

.action-card--amber .action-icon {
    color: var(--amber);
}

/* Data Table */
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
}

.data-table tr:hover td {
    background: var(--surface-hover);
}

.empty-state {
    text-align: center;
    padding: 40px !important;
    color: var(--text-muted);
}

/* Responsive table for mobile */
@media (max-width: 640px) {
    .dash-body {
        padding: 16px;
        gap: 16px;
    }

    .actions-grid {
        grid-template-columns: 1fr;
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
}
</style>