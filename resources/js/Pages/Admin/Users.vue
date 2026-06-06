<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 00-3-3.87" />
                            <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="page-title">Manage Users</h2>
                        <p class="page-sub">Add, edit, or remove system users</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="dash-body">
            <div class="card">
                <div class="card-header">
                    <span class="card-label">Users</span>
                    <button @click="openCreateModal" class="btn-add">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                        </svg>
                        Add New User
                    </button>
                </div>

                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td data-label="Name">{{ user.name }}</td>
                                <td data-label="Email">{{ user.email }}</td>
                                <td data-label="Role">
                                    <span
                                        :class="['role-badge', user.role === 'admin' ? 'role-admin' : 'role-employee']">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <button @click="editUser(user)" class="action-btn action-btn--edit">Edit</button>
                                    <button @click="deleteUser(user)"
                                        class="action-btn action-btn--delete">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td colspan="4" class="empty-state">No users found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal (Dark Theme) -->
        <div v-if="showCreateModal || showEditModal" class="modal-overlay">
            <div class="modal-container">
                <h3 class="modal-title">{{ formTitle }}</h3>
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" v-model="form.name" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" v-model="form.email" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" v-model="form.password" :required="!isEdit" class="form-input">
                        <p v-if="isEdit" class="form-hint">Leave blank to keep current password</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select v-model="form.role" required class="form-select">
                            <option value="employee">Employee</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="button" @click="closeModal" class="modal-btn modal-btn--cancel">Cancel</button>
                        <button type="submit" class="modal-btn modal-btn--confirm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: Array,
});

// Safe route helper (bypass Ziggy) – unchanged
const safeRoute = (name, id = null) => {
    const routes = {
        'admin.users.index': '/admin/users',
        'admin.users.store': '/admin/users',
        'admin.users.update': (id) => `/admin/users/${id}`,
        'admin.users.destroy': (id) => `/admin/users/${id}`,
    };
    if (name === 'admin.users.update' || name === 'admin.users.destroy') {
        return routes[name](id);
    }
    return routes[name] || `/${name.replace(/\./g, '-')}`;
};

const showCreateModal = ref(false);
const showEditModal = ref(false);
const form = ref({
    id: null,
    name: '',
    email: '',
    password: '',
    role: 'employee',
});

const isEdit = ref(false);
const formTitle = ref('');

const openCreateModal = () => {
    isEdit.value = false;
    formTitle.value = 'Add New User';
    form.value = { id: null, name: '', email: '', password: '', role: 'employee' };
    showCreateModal.value = true;
};

const editUser = (user) => {
    isEdit.value = true;
    formTitle.value = 'Edit User';
    form.value = {
        id: user.id,
        name: user.name,
        email: user.email,
        password: '',
        role: user.role,
    };
    showEditModal.value = true;
};

const deleteUser = (user) => {
    if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        router.delete(safeRoute('admin.users.destroy', user.id), {
            preserveScroll: true,
        });
    }
};

const submitForm = () => {
    if (isEdit.value) {
        router.put(safeRoute('admin.users.update', form.value.id), form.value, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        router.post(safeRoute('admin.users.store'), form.value, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    isEdit.value = false;
    form.value = { id: null, name: '', email: '', password: '', role: 'employee' };
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

/* ===== CARD ===== */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    transition: border-color 0.2s;
}

.card:hover {
    border-color: var(--border-hover);
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.card-label {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
}

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(52, 211, 153, 0.12);
    border: 1px solid rgba(52, 211, 153, 0.25);
    color: var(--success);
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add:hover {
    background: var(--success);
    color: #0a0f1e;
    border-color: var(--success);
}

/* ===== DATA TABLE ===== */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

.data-table th {
    text-align: left;
    padding: 14px 20px;
    background: rgba(0, 0, 0, 0.35);
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.08em;
    border-bottom: 1px solid var(--border);
}

.data-table td {
    padding: 16px 20px;
    color: var(--text);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tr:hover td {
    background: rgba(255, 255, 255, 0.05);
    transition: background 0.2s ease;
}

/* Role badges */
.role-badge {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    backdrop-filter: blur(4px);
}

.role-admin {
    background: rgba(248, 113, 113, 0.15);
    color: var(--danger);
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.role-employee {
    background: rgba(52, 211, 153, 0.15);
    color: var(--success);
    border: 1px solid rgba(52, 211, 153, 0.3);
}

/* Action buttons – glass style */
.action-btn {
    background: rgba(255, 255, 255, 0.05);
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--text-muted);
}

.action-btn--edit:hover {
    background: var(--primary);
    color: #0a0f1e;
    transform: translateY(-1px);
}

.action-btn--delete:hover {
    background: var(--danger);
    color: #0a0f1e;
    transform: translateY(-1px);
}

.actions-cell {
    display: flex;
    gap: 8px;
}

.empty-state {
    text-align: center;
    padding: 48px !important;
    color: var(--text-muted);
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
    max-width: 500px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 18px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.form-input,
.form-select {
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.5);
}

.form-hint {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 4px;
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

@media (max-width: 640px) {
    .dash-body {
        padding: 16px;
        gap: 16px;
    }

    .card-header {
        padding: 16px;
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
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
        border-radius: 12px;
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

    .actions-cell {
        justify-content: flex-end;
        width: 100%;
    }

    .modal-container {
        width: 95%;
        margin: 16px;
        padding: 20px;
    }
}
</style>