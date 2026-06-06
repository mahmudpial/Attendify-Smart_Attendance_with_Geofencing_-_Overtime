<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const canLogin = page.props.canLogin ?? false;
const canRegister = page.props.canRegister ?? false;
const laravelVersion = page.props.laravelVersion ?? '';
const phpVersion = page.props.phpVersion ?? '';
const auth = page.props.auth ?? { user: null };

// Safe route function – falls back to plain URLs if Ziggy is not available
const safeRoute = (name, params = {}) => {
    if (typeof route !== 'undefined') {
        try {
            return route(name, params);
        } catch (e) {
            console.warn(`Ziggy route "${name}" not found, falling back.`);
            return `/${name}`;
        }
    }
    return `/${name}`;
};

const dashboardUrl = computed(() => safeRoute('dashboard'));
const loginUrl = computed(() => safeRoute('login'));
const registerUrl = computed(() => safeRoute('register'));
</script>

<template>
    <div class="welcome-root">

        <!-- Animated background grid -->
        <div class="bg-grid" aria-hidden="true"></div>

        <!-- Glowing orbs -->
        <div class="orb orb-1" aria-hidden="true"></div>
        <div class="orb orb-2" aria-hidden="true"></div>
        <div class="orb orb-3" aria-hidden="true"></div>

        <!-- Top Nav -->
        <nav v-if="canLogin" class="nav">
            <div class="nav-brand">
                <span class="nav-icon">⏱</span>
                <span class="nav-title">Attendify</span>
            </div>
            <div class="nav-actions">
                <Link v-if="auth.user" :href="dashboardUrl" class="btn btn-ghost">
                    Dashboard
                </Link>
                <template v-else>
                    <Link :href="loginUrl" class="btn btn-ghost">Log in</Link>
                    <Link v-if="canRegister" :href="registerUrl" class="btn btn-primary">
                        Register
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero -->
        <main class="hero">

            <!-- Badge -->
            <div class="badge">
                <span class="badge-dot"></span>
                Smart Attendance · Geofencing · Overtime
            </div>

            <!-- Headline -->
            <h1 class="headline">
                <span class="headline-line">Track Time.</span>
                <span class="headline-line accent">Work Smarter.</span>
            </h1>

            <!-- Sub -->
            <p class="subline">
                A production-ready attendance platform with GPS punch-in, geofencing,
                overtime calculation, leave management, and real-time reporting.
            </p>

            <!-- CTA -->
            <div class="cta-group">
                <Link :href="loginUrl" class="btn btn-cta">
                    <span>Get Started</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" class="btn-arrow">
                        <path fill-rule="evenodd"
                            d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                            clip-rule="evenodd" />
                    </svg>
                </Link>
                <Link v-if="canRegister" :href="registerUrl" class="btn btn-outline">
                    Create Account
                </Link>
            </div>

            <!-- Feature pills -->
            <div class="pill-row">
                <span class="pill">📍 GPS Tracking</span>
                <span class="pill">🛡️ Geofencing</span>
                <span class="pill">⏰ Overtime Calc</span>
                <span class="pill">📋 Leave Mgmt</span>
                <span class="pill">📊 CSV Reports</span>
                <span class="pill">🔔 Notifications</span>
            </div>

        </main>

        <!-- Stats strip -->
        <section class="stats-strip">
            <div class="stat">
                <span class="stat-value">2</span>
                <span class="stat-label">Roles</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-value">GPS</span>
                <span class="stat-label">Every Punch</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-value">Auto</span>
                <span class="stat-label">Overtime</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-value">CSV</span>
                <span class="stat-label">Export</span>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <span class="footer-stack">
                Laravel v{{ laravelVersion }} &nbsp;·&nbsp; PHP v{{ phpVersion }} &nbsp;·&nbsp; Vue 3 &nbsp;·&nbsp;
                Inertia.js
                &nbsp;·&nbsp; Supabase
            </span>
        </footer>

    </div>
</template>

<style scoped>
/* ─── Tokens ─────────────────────────────────────────── */
:root {
    --bg: #080c14;
    --surface: rgba(255, 255, 255, 0.04);
    --border: rgba(255, 255, 255, 0.08);
    --text: #e8edf5;
    --muted: #6b7a99;
    --accent: #3b82f6;
    --accent-glow: rgba(59, 130, 246, 0.35);
    --accent2: #06b6d4;
    --green: #10b981;
    --radius: 14px;
}

/* ─── Root ───────────────────────────────────────────── */
.welcome-root {
    position: relative;
    min-height: 100vh;
    background: #080c14;
    color: var(--text);
    font-family: 'Sora', 'DM Sans', sans-serif;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* ─── Background grid ────────────────────────────────── */
.bg-grid {
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
    background-size: 48px 48px;
    mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, black 30%, transparent 100%);
    pointer-events: none;
    z-index: 0;
}

/* ─── Orbs ───────────────────────────────────────────── */
.orb {
    position: fixed;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
    z-index: 0;
    animation: drift 14s ease-in-out infinite alternate;
}

.orb-1 {
    width: 500px;
    height: 500px;
    background: rgba(59, 130, 246, 0.18);
    top: -180px;
    left: -120px;
    animation-delay: 0s;
}

.orb-2 {
    width: 380px;
    height: 380px;
    background: rgba(6, 182, 212, 0.13);
    top: 10%;
    right: -100px;
    animation-delay: -5s;
}

.orb-3 {
    width: 300px;
    height: 300px;
    background: rgba(16, 185, 129, 0.10);
    bottom: 80px;
    left: 30%;
    animation-delay: -9s;
}

@keyframes drift {
    from {
        transform: translate(0, 0) scale(1);
    }

    to {
        transform: translate(30px, 20px) scale(1.06);
    }
}

/* ─── Nav ────────────────────────────────────────────── */
.nav {
    position: relative;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 40px;
    border-bottom: 1px solid var(--border);
    backdrop-filter: blur(12px);
    background: rgba(8, 12, 20, 0.6);
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.nav-icon {
    font-size: 22px;
    line-height: 1;
}

.nav-title {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.3px;
    color: #fff;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ─── Buttons ─────────────────────────────────────────── */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.btn-ghost {
    color: var(--muted);
    background: transparent;
}

.btn-ghost:hover {
    color: var(--text);
    background: var(--surface);
}

.btn-primary {
    background: var(--accent);
    color: #fff;
    box-shadow: 0 0 20px var(--accent-glow);
}

.btn-primary:hover {
    background: #2563eb;
    box-shadow: 0 0 28px var(--accent-glow);
    transform: translateY(-1px);
}

.btn-cta {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
    padding: 13px 28px;
    font-size: 15px;
    border-radius: 11px;
    box-shadow: 0 0 32px var(--accent-glow);
}

.btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 48px var(--accent-glow);
}

.btn-outline {
    border: 1px solid var(--border);
    color: var(--text);
    background: var(--surface);
    padding: 13px 28px;
    font-size: 15px;
    border-radius: 11px;
}

.btn-outline:hover {
    border-color: rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.07);
    transform: translateY(-2px);
}

.btn-arrow {
    width: 18px;
    height: 18px;
    transition: transform 0.2s;
}

.btn-cta:hover .btn-arrow {
    transform: translateX(3px);
}

/* ─── Hero ───────────────────────────────────────────── */
.hero {
    position: relative;
    z-index: 5;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 80px 24px 48px;
    gap: 28px;
    animation: fadeUp 0.7s ease both;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(24px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 999px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.25);
    font-size: 12px;
    font-weight: 600;
    color: #93c5fd;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    animation: fadeUp 0.7s 0.1s ease both;
}

.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--green);
    box-shadow: 0 0 8px var(--green);
    animation: pulse 2s infinite;
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.4;
    }
}

/* Headline */
.headline {
    display: flex;
    flex-direction: column;
    font-size: clamp(44px, 7vw, 88px);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -2px;
    color: #fff;
    animation: fadeUp 0.7s 0.15s ease both;
}

.headline-line {
    display: block;
}

.accent {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Subline */
.subline {
    max-width: 560px;
    font-size: 17px;
    line-height: 1.7;
    color: var(--muted);
    animation: fadeUp 0.7s 0.2s ease both;
}

/* CTA group */
.cta-group {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    justify-content: center;
    animation: fadeUp 0.7s 0.25s ease both;
}

/* Pills */
.pill-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    animation: fadeUp 0.7s 0.35s ease both;
    margin-top: 8px;
}

.pill {
    padding: 6px 14px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 999px;
    font-size: 13px;
    color: var(--muted);
    transition: all 0.2s;
}

.pill:hover {
    border-color: rgba(59, 130, 246, 0.4);
    color: var(--text);
    background: rgba(59, 130, 246, 0.08);
}

/* ─── Stats strip ─────────────────────────────────────── */
.stats-strip {
    position: relative;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin: 0 40px 40px;
    padding: 28px 40px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    backdrop-filter: blur(12px);
    flex-wrap: wrap;
    gap: 0;
    animation: fadeUp 0.7s 0.4s ease both;
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 40px;
    gap: 4px;
}

.stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.5px;
}

.stat-label {
    font-size: 12px;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-weight: 600;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: var(--border);
}

/* ─── Footer ─────────────────────────────────────────── */
.footer {
    position: relative;
    z-index: 5;
    text-align: center;
    padding: 20px;
    border-top: 1px solid var(--border);
}

.footer-stack {
    font-size: 12px;
    color: var(--muted);
    letter-spacing: 0.02em;
}

/* ─── Responsive ─────────────────────────────────────── */
@media (max-width: 640px) {
    .nav {
        padding: 16px 20px;
    }

    .nav-title {
        font-size: 16px;
    }

    .hero {
        padding: 60px 20px 36px;
        gap: 22px;
    }

    .stat {
        padding: 0 20px;
    }

    .stats-strip {
        margin: 0 16px 24px;
        padding: 20px 16px;
    }

    .headline {
        letter-spacing: -1px;
    }
}
</style>