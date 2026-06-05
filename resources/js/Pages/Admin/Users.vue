<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Add User Button -->
                        <button @click="showCreateModal = true"
                            class="bg-green-600 text-white px-4 py-2 rounded mb-4 hover:bg-green-700">
                            + Add New User
                        </button>

                        <!-- Users Table -->
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border">Name</th>
                                    <th class="px-4 py-2 border">Email</th>
                                    <th class="px-4 py-2 border">Role</th>
                                    <th class="px-4 py-2 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td class="border px-4 py-2">{{ user.name }}</td>
                                    <td class="border px-4 py-2">{{ user.email }}</td>
                                    <td class="border px-4 py-2">
                                        <span
                                            :class="user.role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                            class="px-2 py-1 rounded text-sm">
                                            {{ user.role }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <button @click="editUser(user)"
                                            class="text-blue-600 hover:underline mr-2">Edit</button>
                                        <button @click="deleteUser(user)"
                                            class="text-red-600 hover:underline">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="users.length === 0">
                                    <td colspan="4" class="text-center py-4">No users found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showCreateModal || showEditModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-medium mb-4">{{ formTitle }}</h3>
                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" v-model="form.name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" v-model="form.email" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" v-model="form.password" :required="!isEdit"
                            class="w-full border rounded px-3 py-2">
                        <p v-if="isEdit" class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Role</label>
                        <select v-model="form.role" required class="w-full border rounded px-3 py-2">
                            <option value="employee">Employee</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
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

function editUser(user) {
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
}

function deleteUser(user) {
    if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        router.delete(route('admin.users.destroy', user.id), {
            preserveScroll: true,
        });
    }
}

function submitForm() {
    if (isEdit.value) {
        router.put(route('admin.users.update', form.value.id), form.value, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        router.post(route('admin.users.store'), form.value, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
}

function closeModal() {
    showCreateModal.value = false;
    showEditModal.value = false;
    showEditModal.value = false;
    isEdit.value = false;
    form.value = { id: null, name: '', email: '', password: '', role: 'employee' };
}

function openCreateModal() {
    isEdit.value = false;
    formTitle.value = 'Add New User';
    showCreateModal.value = true;
}
</script>