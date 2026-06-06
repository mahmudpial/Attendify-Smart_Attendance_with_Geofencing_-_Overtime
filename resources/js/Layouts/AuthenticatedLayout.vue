<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

// ---------- EXPLICIT ROUTE MAPPING (hardcoded URLs) ----------
const routeUrls = {
    'dashboard': '/dashboard',
    'profile.edit': '/profile',   // <-- fixed: maps to /profile, not /profile/edit
    'logout': '/logout',
};

// Safe route function that never throws
const safeRoute = (name, params = {}) => {
    // First check hardcoded mapping
    if (routeUrls[name]) return routeUrls[name];

    // Then try Ziggy if available
    if (typeof route !== 'undefined') {
        try {
            return route(name, params);
        } catch (e) {
            // fallback
        }
    }
    // Ultimate fallback: replace dots with hyphens
    return `/${name.replace(/\./g, '-')}`;
};

// Safe active route checker
const isActiveRoute = (routeName) => {
    if (typeof route !== 'undefined') {
        try {
            return route().current(routeName);
        } catch (e) {
            return false;
        }
    }
    return false;
};

const showingNavigationDropdown = ref(false);
const page = usePage();

const user = computed(() => page.props.auth?.user || null);
const userName = computed(() => user.value?.name || 'Guest');
const userEmail = computed(() => user.value?.email || '');

// Pre‑computed URLs using safeRoute
const dashboardUrl = safeRoute('dashboard');
const profileUrl = safeRoute('profile.edit');
const logoutUrl = safeRoute('logout');

// Computed active states
const isDashboardActive = computed(() => isActiveRoute('dashboard'));
</script>

<template>
    <div class="layout">
        <div class="min-h-screen bg-dark">
            <nav class="nav-glass sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <div class="flex shrink-0 items-center">
                            <Link :href="dashboardUrl">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-primary" />
                            </Link>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:space-x-8">
                            <NavLink :href="dashboardUrl" :active="isDashboardActive" class="nav-link">
                                Dashboard
                            </NavLink>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="relative ms-3" v-if="user">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="user-trigger">
                                            <span class="user-name">{{ userName }}</span>
                                            <svg class="dropdown-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="profileUrl" class="dropdown-item">Profile</DropdownLink>
                                        <DropdownLink :href="logoutUrl" method="post" as="button" class="dropdown-item">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="hamburger-btn">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden border-t border-border-dark">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="dashboardUrl" :active="isDashboardActive" class="mobile-nav-link">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-4 pb-3 border-t border-border-dark" v-if="user">
                        <div class="px-4">
                            <div class="text-base font-medium text-text">{{ userName }}</div>
                            <div class="text-sm font-medium text-text-muted">{{ userEmail }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="profileUrl" class="mobile-nav-link">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="logoutUrl" method="post" as="button" class="mobile-nav-link">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" class="pt-8 pb-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Your existing styles – unchanged */
.layout {
    --bg-dark: #0a0f1e;
    --surface: rgba(255, 255, 255, 0.045);
    --surface-hover: rgba(255, 255, 255, 0.075);
    --border-dark: rgba(255, 255, 255, 0.08);
    --text-primary: #f0f4ff;
    --text-muted: #8b97b8;
    --text-disabled: #545e7a;
    --primary: #4f8ef7;
    --primary-dim: rgba(79, 142, 247, 0.15);
}

.bg-dark {
    background-color: var(--bg-dark);
}

.nav-glass {
    background: rgba(10, 15, 30, 0.8);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border-dark);
}

.nav-link {
    color: var(--text-muted);
    font-weight: 500;
    transition: all 0.2s ease;
    padding: 0.5rem 0;
    border-bottom: 2px solid transparent;
}

.nav-link:hover {
    color: var(--text-primary);
    border-bottom-color: var(--primary);
}

.router-link-active .nav-link {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.user-trigger {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--surface);
    border: 1px solid var(--border-dark);
    border-radius: 999px;
    padding: 0.4rem 1rem 0.4rem 1.2rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    transition: all 0.2s;
    cursor: pointer;
}

.user-trigger:hover {
    background: var(--surface-hover);
    border-color: rgba(79, 142, 247, 0.4);
}

.user-name {
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dropdown-icon {
    width: 16px;
    height: 16px;
    stroke: currentColor;
    transition: transform 0.2s;
}

.user-trigger:hover .dropdown-icon {
    transform: translateY(2px);
}

:deep(.dropdown-content) {
    background: var(--surface);
    backdrop-filter: blur(8px);
    border: 1px solid var(--border-dark);
    border-radius: 12px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
}

.dropdown-item {
    display: block;
    padding: 0.5rem 1rem;
    color: var(--text-muted);
    transition: all 0.15s;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background: var(--surface-hover);
    color: var(--text-primary);
}

.hamburger-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    border-radius: 0.5rem;
    color: var(--text-muted);
    transition: all 0.2s;
}

.hamburger-btn:hover {
    background: var(--surface-hover);
    color: var(--text-primary);
}

.mobile-nav-link {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    font-weight: 500;
    color: var(--text-muted);
    transition: all 0.15s;
}

.mobile-nav-link:hover {
    background: var(--surface-hover);
    color: var(--text-primary);
}

.border-border-dark {
    border-color: var(--border-dark);
}

.text-text {
    color: var(--text-primary);
}

.text-text-muted {
    color: var(--text-muted);
}
</style>