<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const canLogin = page.props.canLogin ?? false;
const canRegister = page.props.canRegister ?? false;
const laravelVersion = page.props.laravelVersion ?? '';
const phpVersion = page.props.phpVersion ?? '';
const auth = page.props.auth ?? { user: null };

const safeRoute = (name, params = {}) => {
    if (typeof route !== 'undefined') {
        try { return route(name, params); }
        catch (e) { return `/${name}`; }
    }
    return `/${name}`;
};

const dashboardUrl = computed(() => safeRoute('dashboard'));
const loginUrl = computed(() => safeRoute('login'));
const registerUrl = computed(() => safeRoute('register'));
</script>

<template>
    <div class="root">

        <!-- ── Noise texture overlay ── -->
        <div class="noise" aria-hidden="true"></div>

        <!-- ── Radial spotlight ── -->
        <div class="spotlight" aria-hidden="true"></div>

        <!-- ══════════════════════════════════
             NAV
        ═══════════════════════════════════ -->
        <header class="nav">
            <div class="nav-inner">
                <!-- Brand -->
                <div class="brand">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <span class="brand-name">Attendify</span>
                </div>

                <!-- Nav links -->
                <nav v-if="canLogin" class="nav-links">
                    <Link v-if="auth.user" :href="dashboardUrl" class="nav-btn nav-btn--ghost">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="loginUrl" class="nav-btn nav-btn--ghost">Sign in</Link>
                        <Link v-if="canRegister" :href="registerUrl" class="nav-btn nav-btn--solid">
                            Get started →
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- ══════════════════════════════════
             HERO
        ═══════════════════════════════════ -->
        <main class="hero">

            <!-- Status pill -->
            <div class="status-pill" style="animation-delay:0s">
                <span class="status-dot"></span>
                <span>Production Ready &nbsp;·&nbsp; v1.0.0</span>
            </div>

            <!-- Headline -->
            <h1 class="headline" style="animation-delay:.08s">
                Attendance Management<br>
                <em class="headline-em">Reimagined.</em>
            </h1>

            <!-- Sub -->
            <p class="sub" style="animation-delay:.16s">
                GPS punch-in, real-time geofencing, automatic overtime,<br class="br-d">
                leave workflows &amp; payroll-ready CSV exports — all in one platform.
            </p>

            <!-- CTA row -->
            <div class="cta-row" style="animation-delay:.24s">
                <Link :href="loginUrl" class="cta-primary">
                    Start Tracking
                    <svg class="cta-arrow" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                            clip-rule="evenodd" />
                    </svg>
                </Link>
                <Link v-if="canRegister" :href="registerUrl" class="cta-secondary">
                    Create free account
                </Link>
            </div>

            <!-- ── Feature grid ── -->
            <div class="feature-grid" style="animation-delay:.32s">
                <div class="feature-card">
                    <div class="fc-icon fc-icon--blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="fc-body">
                        <div class="fc-title">GPS Punch In/Out</div>
                        <div class="fc-desc">Coordinates &amp; IP captured on every clock event</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="fc-icon fc-icon--emerald">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                    </div>
                    <div class="fc-body">
                        <div class="fc-title">Geofencing</div>
                        <div class="fc-desc">Configurable radius enforcement on the backend</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="fc-icon fc-icon--amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="fc-body">
                        <div class="fc-title">Overtime Engine</div>
                        <div class="fc-desc">Auto-calculated with 1.5× / 2.0× multipliers</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="fc-icon fc-icon--violet">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="fc-body">
                        <div class="fc-title">Leave Management</div>
                        <div class="fc-desc">Sick / casual / annual with auto balance deduction</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="fc-icon fc-icon--rose">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                    </div>
                    <div class="fc-body">
                        <div class="fc-title">Reports &amp; CSV</div>
                        <div class="fc-desc">Date-range filtering &amp; one-click payroll export</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="fc-icon fc-icon--cyan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 17H2a3 3 0 004-3V9a8 8 0 0116 0v5a3 3 0 004 3z" />
                            <path d="M13.73 21a2 2 0 01-3.46 0" />
                        </svg>
                    </div>
                    <div class="fc-body">
                        <div class="fc-title">Smart Notifications</div>
                        <div class="fc-desc">Punch reminders, late alerts &amp; leave status emails</div>
                    </div>
                </div>
            </div>

        </main>

        <!-- ══════════════════════════════════
             STATS BAR
        ═══════════════════════════════════ -->
        <section class="stats-bar">
            <div class="stats-inner">
                <div class="stat">
                    <span class="stat-n">2</span>
                    <span class="stat-l">Access Roles</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat">
                    <span class="stat-n">GPS</span>
                    <span class="stat-l">Every Punch</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat">
                    <span class="stat-n">Auto</span>
                    <span class="stat-l">Overtime Calc</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat">
                    <span class="stat-n">CSV</span>
                    <span class="stat-l">Payroll Export</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat">
                    <span class="stat-n">Free</span>
                    <span class="stat-l">Open Source</span>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════
             FOOTER
        ═══════════════════════════════════ -->
        <footer class="footer">
            <span class="footer-stack">
                Laravel v{{ laravelVersion }} &nbsp;·&nbsp; PHP v{{ phpVersion }} &nbsp;·&nbsp; Vue 3 &nbsp;·&nbsp;
                Inertia.js
                &nbsp;·&nbsp; Supabase &nbsp;·&nbsp; Render
            </span>
            <span class="footer-copy">Built by <a href="https://github.com/mahmudpial" target="_blank">Pial
                    Mahmud</a></span>
        </footer>

    </div>
</template>

<style scoped>
/* ── Google Fonts import (add to app.blade.php <head> if needed) ───────────
   <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
──────────────────────────────────────────────────────────────────────────── */

/* ════════════════════════════════
   TOKENS
════════════════════════════════ */
.root {
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
    --blue-glow: rgba(79, 142, 247, 0.3);
    --emerald: #34d399;
    --amber: #fbbf24;
    --violet: #a78bfa;
    --rose: #f87171;
    --cyan: #22d3ee;
    --r: 12px;
    --font: 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif;
}

/* ════════════════════════════════
   BASE
════════════════════════════════ */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.root {
    min-height: 100vh;
    background: var(--bg);
    color: var(--text);
    font-family: var(--font);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow-x: hidden;
}

/* ── Noise ── */
.noise {
    position: fixed;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
    opacity: .025;
}

/* ── Spotlight ── */
.spotlight {
    position: fixed;
    top: -30vh;
    left: 50%;
    transform: translateX(-50%);
    width: 900px;
    height: 600px;
    background: radial-gradient(ellipse at center, rgba(79, 142, 247, 0.22) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

/* ════════════════════════════════
   NAV
════════════════════════════════ */
.nav {
    position: relative;
    z-index: 20;
    border-bottom: 1px solid var(--border);
    background: rgba(10, 15, 30, 0.8);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

.nav-inner {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 32px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Brand */
.brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand-icon {
    width: 36px;
    height: 36px;
    background: var(--blue-dim);
    border: 1px solid rgba(79, 142, 247, 0.3);
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--blue);
}

.brand-icon svg {
    width: 18px;
    height: 18px;
}

.brand-name {
    font-size: 16px;
    font-weight: 700;
    letter-spacing: -.2px;
    color: var(--text);
}

/* Nav links */
.nav-links {
    display: flex;
    align-items: center;
    gap: 10px;
}

.nav-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all .18s ease;
    white-space: nowrap;
}

.nav-btn--ghost {
    color: var(--text-2);
}

.nav-btn--ghost:hover {
    color: var(--text);
    background: var(--surface);
}

.nav-btn--solid {
    background: var(--blue);
    color: #fff;
    box-shadow: 0 0 24px var(--blue-glow);
}

.nav-btn--solid:hover {
    background: #3b7cf0;
    transform: translateY(-1px);
    box-shadow: 0 0 32px var(--blue-glow);
}

/* ════════════════════════════════
   HERO
════════════════════════════════ */
.hero {
    position: relative;
    z-index: 10;
    flex: 1;
    max-width: 1160px;
    margin: 0 auto;
    width: 100%;
    padding: 96px 32px 72px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0;
}

/* Staggered fade-up for children that have animation-delay set inline */
.hero>* {
    animation: fadeUp .6s cubic-bezier(.22, 1, .36, 1) both;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status pill */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 5px 14px;
    border-radius: 999px;
    border: 1px solid rgba(52, 211, 153, 0.3);
    background: rgba(52, 211, 153, 0.08);
    font-size: 12px;
    font-weight: 600;
    color: var(--emerald);
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 32px;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--emerald);
    box-shadow: 0 0 8px var(--emerald);
    animation: blink 2s ease-in-out infinite;
}

@keyframes blink {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: .3;
    }
}

/* Headline */
.headline {
    font-size: clamp(40px, 6.5vw, 80px);
    font-weight: 800;
    line-height: 1.08;
    letter-spacing: -2.5px;
    color: var(--text);
    margin-bottom: 28px;
}

.headline-em {
    font-style: italic;
    background: linear-gradient(135deg, var(--blue) 0%, var(--cyan) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Sub */
.sub {
    font-size: 18px;
    line-height: 1.75;
    color: var(--text-2);
    max-width: 580px;
    margin-bottom: 44px;
}

/* CTA */
.cta-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 72px;
}

.cta-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 32px;
    background: var(--blue);
    color: #fff;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 0 0 1px rgba(79, 142, 247, .4), 0 8px 32px var(--blue-glow);
    transition: all .2s ease;
    letter-spacing: -.1px;
}

.cta-primary:hover {
    background: #3b7cf0;
    transform: translateY(-2px);
    box-shadow: 0 0 0 1px rgba(79, 142, 247, .6), 0 12px 40px rgba(79, 142, 247, .45);
}

.cta-arrow {
    width: 16px;
    height: 16px;
    transition: transform .2s;
}

.cta-primary:hover .cta-arrow {
    transform: translateX(3px);
}

.cta-secondary {
    display: inline-flex;
    align-items: center;
    padding: 14px 28px;
    border: 1px solid var(--border-h);
    color: var(--text-2);
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    background: var(--surface);
    transition: all .2s ease;
}

.cta-secondary:hover {
    color: var(--text);
    border-color: rgba(255, 255, 255, .25);
    background: var(--surface-h);
    transform: translateY(-2px);
}

/* ── Feature grid ── */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    width: 100%;
}

.feature-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 22px 22px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    text-align: left;
    transition: all .2s ease;
    cursor: default;
}

.feature-card:hover {
    background: var(--surface-h);
    border-color: var(--border-h);
    transform: translateY(-2px);
}

.fc-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fc-icon svg {
    width: 18px;
    height: 18px;
}

.fc-icon--blue {
    background: rgba(79, 142, 247, .15);
    color: var(--blue);
    border: 1px solid rgba(79, 142, 247, .25);
}

.fc-icon--emerald {
    background: rgba(52, 211, 153, .12);
    color: var(--emerald);
    border: 1px solid rgba(52, 211, 153, .22);
}

.fc-icon--amber {
    background: rgba(251, 191, 36, .12);
    color: var(--amber);
    border: 1px solid rgba(251, 191, 36, .22);
}

.fc-icon--violet {
    background: rgba(167, 139, 250, .12);
    color: var(--violet);
    border: 1px solid rgba(167, 139, 250, .22);
}

.fc-icon--rose {
    background: rgba(248, 113, 113, .12);
    color: var(--rose);
    border: 1px solid rgba(248, 113, 113, .22);
}

.fc-icon--cyan {
    background: rgba(34, 211, 238, .12);
    color: var(--cyan);
    border: 1px solid rgba(34, 211, 238, .22);
}

.fc-body {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.fc-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
}

.fc-desc {
    font-size: 13px;
    color: var(--text-2);
    line-height: 1.5;
}

/* ════════════════════════════════
   STATS BAR
════════════════════════════════ */
.stats-bar {
    position: relative;
    z-index: 10;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    background: rgba(15, 22, 41, 0.6);
    backdrop-filter: blur(12px);
}

.stats-inner {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0;
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 28px 48px;
    gap: 4px;
}

.stat-n {
    font-size: 22px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.5px;
}

.stat-l {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .08em;
}

.stat-sep {
    width: 1px;
    height: 36px;
    background: var(--border);
}

/* ════════════════════════════════
   FOOTER
════════════════════════════════ */
.footer {
    position: relative;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding: 20px 48px;
    background: rgba(10, 15, 30, 0.9);
}

.footer-stack,
.footer-copy {
    font-size: 12px;
    color: var(--text-3);
}

.footer-copy {
    color: var(--text-3);
}

/* ════════════════════════════════
   RESPONSIVE
════════════════════════════════ */
.br-d {
    display: block;
}

@media (max-width: 900px) {
    .feature-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .stat {
        padding: 24px 28px;
    }
}

@media (max-width: 640px) {
    .nav-inner {
        padding: 0 20px;
    }

    .hero {
        padding: 64px 20px 48px;
    }

    .headline {
        letter-spacing: -1.5px;
        margin-bottom: 20px;
    }

    .sub {
        font-size: 16px;
        margin-bottom: 36px;
    }

    .br-d {
        display: none;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }

    .stat {
        padding: 18px 20px;
    }

    .stat-sep {
        display: none;
    }

    .stats-inner {
        flex-wrap: wrap;
    }

    .footer {
        padding: 16px 20px;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}
</style>