<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employee Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Location permission warning -->
                        <div v-if="locationError" class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded-md">
                            ⚠️ {{ locationError }}. Punch without location may be restricted.
                        </div>

                        <!-- Flash message from server -->
                        <div v-if="flashMessage" :class="flashType" class="p-4 rounded-md mb-4">
                            {{ flashMessage }}
                        </div>

                        <!-- Punch Buttons -->
                        <div class="flex space-x-4 mb-8">
                            <button @click="punchIn" :disabled="isLoading || (punchedIn && !punchedOut)"
                                class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 flex items-center gap-2">
                                <span v-if="isLoading"
                                    class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                {{ isLoading ? 'Getting location...' : 'Punch In' }}
                            </button>
                            <button @click="punchOut" :disabled="isLoading || !punchedIn || punchedOut"
                                class="px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50 flex items-center gap-2">
                                <span v-if="isLoading"
                                    class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                {{ isLoading ? 'Getting location...' : 'Punch Out' }}
                            </button>
                        </div>

                        <!-- Today's Status -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2">Today's Attendance</h3>
                            <div class="space-y-1">
                                <p><strong>Punch In:</strong> {{ todayAttendance?.punch_in ? new
                                    Date(todayAttendance.punch_in).toLocaleTimeString() : 'Not punched in' }}</p>
                                <p><strong>Punch Out:</strong> {{ todayAttendance?.punch_out ? new
                                    Date(todayAttendance.punch_out).toLocaleTimeString() : 'Not punched out' }}</p>

                                <!-- ✅ Always show normal & overtime hours (default to 0.00) -->
                                <p><strong>🕐 Normal Hours:</strong> {{ normalHours }}</p>
                                <p><strong>⏱️ Overtime Hours:</strong> {{ overtimeHours }}</p>

                                <p v-if="todayAttendance?.punch_in_lat">
                                    <strong>📍 In Location:</strong> {{ todayAttendance.punch_in_lat.toFixed(6) }}, {{
                                        todayAttendance.punch_in_lng.toFixed(6) }}
                                </p>
                                <p v-if="todayAttendance?.punch_in_ip">
                                    <strong>🌐 In IP:</strong> {{ todayAttendance.punch_in_ip }}
                                </p>
                                <p v-if="todayAttendance?.punch_out_lat">
                                    <strong>📍 Out Location:</strong> {{ todayAttendance.punch_out_lat.toFixed(6) }}, {{
                                        todayAttendance.punch_out_lng.toFixed(6) }}
                                </p>
                                <p v-if="todayAttendance?.punch_out_ip">
                                    <strong>🌐 Out IP:</strong> {{ todayAttendance.punch_out_ip }}
                                </p>
                            </div>
                        </div>

                        <!-- Link to history & leave requests -->
                        <div class="flex gap-4">
                            <Link :href="route('my.attendance')" class="text-blue-600 hover:underline">
                                View My Attendance History
                            </Link>
                            <Link :href="route('leave.requests')" class="text-purple-600 hover:underline">
                                Manage Leave
                            </Link>
                        </div>
                    </div>
                </div>
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

// Flash message from Laravel session (via HandleInertiaRequests)
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

// ✅ Computed values for normal & overtime – always show 0.00 if missing or null
const normalHours = computed(() => {
    const val = props.todayAttendance?.normal_hours;
    return (val !== undefined && val !== null) ? val.toFixed(2) : '0.00';
});
const overtimeHours = computed(() => {
    const val = props.todayAttendance?.overtime_hours;
    return (val !== undefined && val !== null) ? val.toFixed(2) : '0.00';
});

const isLoading = ref(false);
const locationError = ref(null);

// Get GPS position with user-friendly error handling
const getCurrentPosition = () => {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Your browser does not support geolocation.'));
            return;
        }
        navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
        });
    });
};

// Punch In
const punchIn = async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    locationError.value = null;

    let lat = null, lng = null;
    try {
        const position = await getCurrentPosition();
        lat = position.coords.latitude;
        lng = position.coords.longitude;
    } catch (err) {
        if (err.code === 1) {
            locationError.value = 'Location permission denied. Please enable location services to punch in from office premises.';
        } else if (err.code === 2) {
            locationError.value = 'Location unavailable. Please check your GPS signal.';
        } else if (err.code === 3) {
            locationError.value = 'Location request timed out. Please try again.';
        } else {
            locationError.value = err.message;
        }
    }

    router.post(route('punch.in'), { latitude: lat, longitude: lng }, {
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

// Punch Out
const punchOut = async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    locationError.value = null;

    let lat = null, lng = null;
    try {
        const position = await getCurrentPosition();
        lat = position.coords.latitude;
        lng = position.coords.longitude;
    } catch (err) {
        if (err.code === 1) {
            locationError.value = 'Location permission denied. Please enable location services.';
        } else if (err.code === 2) {
            locationError.value = 'Location unavailable. Please check your GPS signal.';
        } else if (err.code === 3) {
            locationError.value = 'Location request timed out. Please try again.';
        } else {
            locationError.value = err.message;
        }
    }

    router.post(route('punch.out'), { latitude: lat, longitude: lng }, {
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>