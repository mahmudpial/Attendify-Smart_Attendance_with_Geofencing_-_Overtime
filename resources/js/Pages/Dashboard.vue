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
                        <h2 class="page-title">Employee Dashboard</h2>
                        <p class="page-sub">{{ todayLabel }}</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">

            <!-- ── Location warning ── -->
            <div v-if="locationError" class="alert alert--warn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="alert-icon">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                {{ locationError }}. Punch without location may be restricted.
            </div>

            <!-- ── Flash message (FIXED) ── -->
            <div v-if="flashMessage"
                :class="['alert', flashType === 'bg-green-100 text-green-800' ? 'alert--success' : 'alert--error']">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="alert-icon">
                    <!-- Success icon -->
                    <template v-if="flashType === 'bg-green-100 text-green-800'">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </template>
                    <!-- Error icon -->
                    <template v-else>
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </template>
                </svg>
                {{ flashMessage }}
            </div>

            <!-- ══ PUNCH CARD ════════════════════════════════ -->
            <div class="card punch-card">
                <div class="card-header">
                    <span class="card-label">Clock Actions</span>
                    <span class="live-badge">
                        <span class="live-dot"></span>
                        Live
                    </span>
                </div>

                <div class="punch-buttons">
                    <!-- Punch In -->
                    <button @click="punchIn" :disabled="isLoading || (punchedIn && !punchedOut)"
                        class="punch-btn punch-btn--in">
                        <span v-if="isLoading" class="spinner spinner--dark"></span>
                        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            class="punch-btn-icon">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                        {{ isLoading ? 'Getting location…' : 'Punch In' }}
                    </button>

                    <!-- Punch Out -->
                    <button @click="punchOut" :disabled="isLoading || !punchedIn || punchedOut"
                        class="punch-btn punch-btn--out">
                        <span v-if="isLoading" class="spinner spinner--dark"></span>
                        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            class="punch-btn-icon">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        {{ isLoading ? 'Getting location…' : 'Punch Out' }}
                    </button>
                </div>

                <!-- Punch status indicator -->
                <div class="punch-status-bar">
                    <div class="punch-step" :class="{ 'punch-step--done': todayAttendance?.punch_in }">
                        <div class="punch-step-dot"></div>
                        <span>Punched In</span>
                        <span class="punch-step-time">{{ todayAttendance?.punch_in ? new
                            Date(todayAttendance.punch_in).toLocaleTimeString() : '—' }}</span>
                    </div>
                    <div class="punch-step-line" :class="{ 'punch-step-line--done': todayAttendance?.punch_out }"></div>
                    <div class="punch-step" :class="{ 'punch-step--done': todayAttendance?.punch_out }">
                        <div class="punch-step-dot"></div>
                        <span>Punched Out</span>
                        <span class="punch-step-time">{{ todayAttendance?.punch_out ? new
                            Date(todayAttendance.punch_out).toLocaleTimeString() : '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- ══ STATS ROW ═══════════════════════════════ -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-card-icon stat-card-icon--blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value">{{ normalHours }}<span class="stat-card-unit">h</span></div>
                        <div class="stat-card-label">Normal Hours</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon stat-card-icon--amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value">{{ overtimeHours }}<span class="stat-card-unit">h</span></div>
                        <div class="stat-card-label">Overtime Hours</div>
                    </div>
                </div>

                <div class="stat-card stat-card--wide">
                    <div class="stat-card-icon stat-card-icon--emerald">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value stat-card-value--sm">{{ todayAttendance?.punch_in_ip || '—' }}</div>
                        <div class="stat-card-label">IP Address</div>
                    </div>
                </div>
            </div>

            <!-- ══ TODAY'S DETAIL ════════════════════════════ -->
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Today's Record</span>
                    <span v-if="todayAttendance?.status"
                        :class="['status-badge', 'status-badge--' + todayAttendance.status]">
                        {{ todayAttendance.status }}
                    </span>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-icon detail-icon--blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" y1="12" x2="3" y2="12" />
                            </svg>
                        </div>
                        <div>
                            <div class="detail-key">Punch In</div>
                            <div class="detail-val">{{ todayAttendance?.punch_in ? new
                                Date(todayAttendance.punch_in).toLocaleTimeString() : 'Not punched in' }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon detail-icon--rose">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </div>
                        <div>
                            <div class="detail-key">Punch Out</div>
                            <div class="detail-val">{{ todayAttendance?.punch_out ? new
                                Date(todayAttendance.punch_out).toLocaleTimeString() : 'Not punched out' }}</div>
                        </div>
                    </div>

                    <div v-if="todayAttendance?.punch_in_lat" class="detail-item">
                        <div class="detail-icon detail-icon--emerald">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div>
                            <div class="detail-key">In Location</div>
                            <div class="detail-val detail-val--mono">{{ formatCoordinate(todayAttendance.punch_in_lat,
                                todayAttendance.punch_in_lng) }}</div>
                        </div>
                    </div>

                    <div v-if="todayAttendance?.punch_out_lat" class="detail-item">
                        <div class="detail-icon detail-icon--violet">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div>
                            <div class="detail-key">Out Location</div>
                            <div class="detail-val detail-val--mono">{{ formatCoordinate(todayAttendance.punch_out_lat,
                                todayAttendance.punch_out_lng) }}</div>
                        </div>
                    </div>

                    <div v-if="todayAttendance?.punch_out_ip" class="detail-item">
                        <div class="detail-icon detail-icon--cyan">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" y1="12" x2="22" y2="12" />
                                <path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20" />
                            </svg>
                        </div>
                        <div>
                            <div class="detail-key">Out IP</div>
                            <div class="detail-val detail-val--mono">{{ todayAttendance.punch_out_ip }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══ QUICK LINKS ════════════════════════════ -->
            <div class="quick-links">
                <Link :href="attendanceHistoryUrl" class="quick-link">
                    <div class="quick-link-icon quick-link-icon--blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                    </div>
                    <div class="quick-link-body">
                        <div class="quick-link-title">Attendance History</div>
                        <div class="quick-link-sub">View all your past records</div>
                    </div>
                    <svg class="quick-link-arrow" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                            clip-rule="evenodd" />
                    </svg>
                </Link>

                <Link :href="leaveRequestsUrl" class="quick-link">
                    <div class="quick-link-icon quick-link-icon--violet">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="quick-link-body">
                        <div class="quick-link-title">Manage Leave</div>
                        <div class="quick-link-sub">Request sick, casual or annual leave</div>
                    </div>
                    <svg class="quick-link-arrow" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                            clip-rule="evenodd" />
                    </svg>
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    todayAttendance: Object,
});

const page = usePage();

// Today label
const todayLabel = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

// ---------- SAFE ROUTE ----------
const routeUrls = {
    'my.attendance': '/my-attendance',
    'leave.requests': '/leave-requests',
    'dashboard': '/dashboard',
    'punch.in': '/punch-in',
    'punch.out': '/punch-out',
    'profile.edit': '/profile',
    'logout': '/logout',
};
const safeRoute = (name, params = {}) => {
    if (routeUrls[name]) return routeUrls[name];
    return `/${name.replace(/\./g, '-')}`;
};

// ---------- Coordinate formatter ----------
const formatCoordinate = (lat, lng) => {
    if (lat === undefined || lat === null || isNaN(parseFloat(lat))) return '—';
    if (lng === undefined || lng === null || isNaN(parseFloat(lng))) return '—';
    return `${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
};

// Flash
const flashMessage = computed(() => {
    const flash = page.props.flash;
    if (flash?.success) return flash.success;
    if (flash?.error) return flash.error;
    return null;
});
const flashType = computed(() => {
    if (page.props.flash?.success) return 'bg-green-100 text-green-800';
    if (page.props.flash?.error) return 'bg-red-100 text-red-800';
    return '';
});

// Punch status
const punchedIn = computed(() => props.todayAttendance?.punch_in && !props.todayAttendance?.punch_out);
const punchedOut = computed(() => !!props.todayAttendance?.punch_out);

// Hours
const normalHours = computed(() => {
    let val = props.todayAttendance?.normal_hours;
    if (val === undefined || val === null) return '0.00';
    const num = parseFloat(val);
    return isNaN(num) ? '0.00' : num.toFixed(2);
});
const overtimeHours = computed(() => {
    let val = props.todayAttendance?.overtime_hours;
    if (val === undefined || val === null) return '0.00';
    const num = parseFloat(val);
    return isNaN(num) ? '0.00' : num.toFixed(2);
});

const attendanceHistoryUrl = safeRoute('my.attendance');
const leaveRequestsUrl = safeRoute('leave.requests');

const isLoading = ref(false);
const locationError = ref(null);

const getCurrentPosition = () => {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) { reject(new Error('Your browser does not support geolocation.')); return; }
        navigator.geolocation.getCurrentPosition(resolve, reject, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    });
};

const punchIn = async () => {
    if (isLoading.value) return;
    isLoading.value = true; locationError.value = null;
    let lat = null, lng = null;
    try { const p = await getCurrentPosition(); lat = p.coords.latitude; lng = p.coords.longitude; }
    catch (err) {
        if (err.code === 1) locationError.value = 'Location permission denied. Please enable location services.';
        else if (err.code === 2) locationError.value = 'Location unavailable. Please check your GPS signal.';
        else if (err.code === 3) locationError.value = 'Location request timed out. Please try again.';
        else locationError.value = err.message;
    }
    router.post(safeRoute('punch.in'), { latitude: lat, longitude: lng }, { preserveScroll: true, onFinish: () => { isLoading.value = false; } });
};

const punchOut = async () => {
    if (isLoading.value) return;
    isLoading.value = true; locationError.value = null;
    let lat = null, lng = null;
    try { const p = await getCurrentPosition(); lat = p.coords.latitude; lng = p.coords.longitude; }
    catch (err) {
        if (err.code === 1) locationError.value = 'Location permission denied.';
        else if (err.code === 2) locationError.value = 'Location unavailable.';
        else if (err.code === 3) locationError.value = 'Location request timed out.';
        else locationError.value = err.message;
    }
    router.post(safeRoute('punch.out'), { latitude: lat, longitude: lng }, { preserveScroll: true, onFinish: () => { isLoading.value = false; } });
};
</script>

<style scoped>
/* ════════════════════════════════
   TOKENS  (matches Welcome.vue)
════════════════════════════════ */
* {
    box-sizing: border-box;
}

.dash-body {
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
    display: flex;
    flex-direction: column;
    gap: 20px;
    color: var(--text);
}

/* ════════════════════════════════
   PAGE HEADER  (slot)
════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
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
   ALERTS
════════════════════════════════ */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 18px;
    border-radius: var(--r);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.5;
    border: 1px solid;
}

.alert--warn {
    background: rgba(251, 191, 36, .1);
    border-color: rgba(251, 191, 36, .25);
    color: #fbbf24;
}

.alert--success {
    background: rgba(52, 211, 153, .1);
    border-color: rgba(52, 211, 153, .25);
    color: #34d399;
}

.alert--error {
    background: rgba(248, 113, 113, .1);
    border-color: rgba(248, 113, 113, .25);
    color: #f87171;
}

.alert-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ════════════════════════════════
   CARD
════════════════════════════════ */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 24px;
    transition: border-color .2s;
}

.card:hover {
    border-color: var(--border-h);
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.card-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-3);
}

/* Live badge */
.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 10px;
    border-radius: 999px;
    background: rgba(52, 211, 153, .1);
    border: 1px solid rgba(52, 211, 153, .25);
    font-size: 11px;
    font-weight: 700;
    color: var(--emerald);
    letter-spacing: .04em;
}

.live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--emerald);
    box-shadow: 0 0 6px var(--emerald);
    animation: blink 2s ease-in-out infinite;
}

@keyframes blink {

    0%,
    100% {
        opacity: 1
    }

    50% {
        opacity: .3
    }
}

/* ════════════════════════════════
   PUNCH BUTTONS
════════════════════════════════ */
.punch-buttons {
    display: flex;
    gap: 14px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}

.punch-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    font-family: var(--font);
    cursor: pointer;
    border: none;
    transition: all .2s ease;
    letter-spacing: -.1px;
}

.punch-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
    transform: none !important;
}

.punch-btn--in {
    background: var(--emerald);
    color: #0a0f1e;
    box-shadow: 0 0 28px rgba(52, 211, 153, .35);
}

.punch-btn--in:not(:disabled):hover {
    background: #2ec489;
    transform: translateY(-2px);
    box-shadow: 0 0 40px rgba(52, 211, 153, .5);
}

.punch-btn--out {
    background: var(--rose);
    color: #0a0f1e;
    box-shadow: 0 0 28px rgba(248, 113, 113, .3);
}

.punch-btn--out:not(:disabled):hover {
    background: #ef4444;
    transform: translateY(-2px);
    box-shadow: 0 0 40px rgba(248, 113, 113, .45);
}

.punch-btn-icon {
    width: 18px;
    height: 18px;
}

/* Spinner */
.spinner {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2.5px solid transparent;
    animation: spin .7s linear infinite;
}

.spinner--dark {
    border-top-color: #0a0f1e;
    border-right-color: rgba(10, 15, 30, .3);
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* ── Punch progress bar ── */
.punch-status-bar {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 16px 20px;
    background: rgba(0, 0, 0, .2);
    border-radius: 9px;
    border: 1px solid var(--border);
}

.punch-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    flex: 0 0 auto;
}

.punch-step-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--border-h);
    border: 2px solid var(--border-h);
    transition: all .3s;
}

.punch-step--done .punch-step-dot {
    background: var(--emerald);
    border-color: var(--emerald);
    box-shadow: 0 0 10px rgba(52, 211, 153, .5);
}

.punch-step span {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
}

.punch-step--done span {
    color: var(--text-2);
}

.punch-step-time {
    font-size: 12px !important;
    font-weight: 700 !important;
    color: var(--text) !important;
    text-transform: none !important;
    letter-spacing: 0 !important;
    margin-top: 2px;
}

.punch-step-line {
    flex: 1;
    height: 2px;
    background: var(--border);
    margin: -16px 12px 0;
    transition: background .3s;
}

.punch-step-line--done {
    background: var(--emerald);
}

/* ════════════════════════════════
   STATS ROW
════════════════════════════════ */
.stats-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
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
    transition: all .2s;
}

.stat-card:hover {
    border-color: var(--border-h);
    background: var(--surface-h);
}

.stat-card-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-card-icon svg {
    width: 20px;
    height: 20px;
}

.stat-card-icon--blue {
    background: rgba(79, 142, 247, .15);
    color: var(--blue);
    border: 1px solid rgba(79, 142, 247, .25);
}

.stat-card-icon--amber {
    background: rgba(251, 191, 36, .12);
    color: var(--amber);
    border: 1px solid rgba(251, 191, 36, .22);
}

.stat-card-icon--emerald {
    background: rgba(52, 211, 153, .12);
    color: var(--emerald);
    border: 1px solid rgba(52, 211, 153, .22);
}

.stat-card-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.5px;
    line-height: 1;
}

.stat-card-value--sm {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0;
}

.stat-card-unit {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-3);
    margin-left: 2px;
}

.stat-card-label {
    font-size: 12px;
    color: var(--text-3);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-top: 4px;
}

/* ════════════════════════════════
   DETAIL GRID
════════════════════════════════ */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: rgba(0, 0, 0, .2);
    border-radius: 9px;
    border: 1px solid var(--border);
}

.detail-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.detail-icon svg {
    width: 16px;
    height: 16px;
}

.detail-icon--blue {
    background: rgba(79, 142, 247, .15);
    color: var(--blue);
    border: 1px solid rgba(79, 142, 247, .2);
}

.detail-icon--rose {
    background: rgba(248, 113, 113, .12);
    color: var(--rose);
    border: 1px solid rgba(248, 113, 113, .2);
}

.detail-icon--emerald {
    background: rgba(52, 211, 153, .12);
    color: var(--emerald);
    border: 1px solid rgba(52, 211, 153, .2);
}

.detail-icon--violet {
    background: rgba(167, 139, 250, .12);
    color: var(--violet);
    border: 1px solid rgba(167, 139, 250, .2);
}

.detail-icon--cyan {
    background: rgba(34, 211, 238, .12);
    color: var(--cyan);
    border: 1px solid rgba(34, 211, 238, .2);
}

.detail-key {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .06em;
}

.detail-val {
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
    margin-top: 3px;
}

.detail-val--mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
}

/* Status badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
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
   QUICK LINKS
════════════════════════════════ */
.quick-links {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 22px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    text-decoration: none;
    transition: all .2s ease;
}

.quick-link:hover {
    background: var(--surface-h);
    border-color: var(--border-h);
    transform: translateY(-2px);
}

.quick-link-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-link-icon svg {
    width: 20px;
    height: 20px;
}

.quick-link-icon--blue {
    background: rgba(79, 142, 247, .15);
    color: var(--blue);
    border: 1px solid rgba(79, 142, 247, .25);
}

.quick-link-icon--violet {
    background: rgba(167, 139, 250, .12);
    color: var(--violet);
    border: 1px solid rgba(167, 139, 250, .22);
}

.quick-link-body {
    flex: 1;
}

.quick-link-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
}

.quick-link-sub {
    font-size: 12px;
    color: var(--text-3);
    margin-top: 2px;
}

.quick-link-arrow {
    width: 16px;
    height: 16px;
    color: var(--text-3);
    transition: transform .2s;
}

.quick-link:hover .quick-link-arrow {
    transform: translateX(3px);
    color: var(--text-2);
}

/* ════════════════════════════════
   RESPONSIVE
════════════════════════════════ */
@media (max-width: 900px) {
    .stats-row {
        grid-template-columns: 1fr 1fr;
    }

    .stat-card--wide {
        grid-column: span 2;
    }
}

@media (max-width: 640px) {
    .dash-body {
        padding: 20px 16px;
        gap: 16px;
    }

    .stats-row {
        grid-template-columns: 1fr;
    }

    .stat-card--wide {
        grid-column: span 1;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .quick-links {
        grid-template-columns: 1fr;
    }

    .punch-buttons {
        flex-direction: column;
    }

    .punch-btn {
        justify-content: center;
    }
}
</style>