<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="delete-section">
        <header class="delete-header">
            <h2 class="delete-title">Delete Account</h2>
            <p class="delete-description">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="delete-btn">
            Delete Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="modal-content">
                <h2 class="modal-title">
                    Are you sure you want to delete your account?
                </h2>

                <p class="modal-description">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="modal-form-group">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                        class="modal-password-input" placeholder="Password" @keyup.enter="deleteUser" />
                    <InputError :message="form.errors.password" class="modal-error" />
                </div>

                <div class="modal-actions">
                    <SecondaryButton @click="closeModal" class="modal-btn modal-btn--cancel">
                        Cancel
                    </SecondaryButton>
                    <DangerButton class="modal-btn modal-btn--danger" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing" @click="deleteUser">
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style scoped>
/* Dark theme tokens – matches parent profile page */
.delete-section {
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
    --danger-dim: rgba(248, 113, 113, 0.12);
    --success: #34d399;
    --warning: #fbbf24;
    --info: #a78bfa;
    --r: 12px;
    --font: 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif;
}

.delete-header {
    margin-bottom: 24px;
}

.delete-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.2px;
    margin-bottom: 6px;
}

.delete-description {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
}

.delete-btn {
    background: var(--danger-dim);
    border: 1px solid rgba(248, 113, 113, 0.3);
    color: var(--danger);
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.2s;
}

.delete-btn:hover {
    background: var(--danger);
    color: #0a0f1e;
    border-color: var(--danger);
}

/* Modal styling (dark theme) */
:deep(.modal-content) {
    background: var(--surface) !important;
    backdrop-filter: blur(12px);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

.modal-content {
    padding: 24px;
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.2px;
    margin-bottom: 8px;
}

.modal-description {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 20px;
}

.modal-form-group {
    margin-bottom: 20px;
}

.modal-password-input {
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 14px;
    color: var(--text);
    font-size: 14px;
    transition: all 0.2s;
}

.modal-password-input:focus {
    outline: none;
    border-color: var(--danger);
    background: rgba(0, 0, 0, 0.5);
}

.modal-error {
    font-size: 12px;
    color: var(--danger);
    margin-top: 4px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
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

.modal-btn--danger {
    background: var(--danger-dim);
    border: 1px solid rgba(248, 113, 113, 0.3);
    color: var(--danger);
}

.modal-btn--danger:hover:not(:disabled) {
    background: var(--danger);
    color: #0a0f1e;
}

.opacity-25 {
    opacity: 0.25;
}
</style>