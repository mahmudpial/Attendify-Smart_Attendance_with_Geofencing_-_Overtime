<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveRequestController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard (requires auth & email verification)
Route::get('/dashboard', [DashboardController::class, '__invoke'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes (auth only, no verified required)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authenticated & verified routes (core attendance + leave + admin)
Route::middleware(['auth', 'verified'])->group(function () {
    // Employee: punch in/out & history
    Route::post('/punch-in', [AttendanceController::class, 'punchIn'])->name('punch.in');
    Route::post('/punch-out', [AttendanceController::class, 'punchOut'])->name('punch.out');
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('my.attendance');

    // Employee: leave management
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave.requests');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave.requests.store');

    // ========== ADMIN ROUTES ==========
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        // Attendance management
        Route::get('/attendances', [AttendanceController::class, 'adminIndex'])->name('admin.attendances');

        // Reports & export
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
        Route::post('/reports/export', [ReportController::class, 'export'])->name('admin.reports.export');

        // User management (CRUD)
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Leave request management (admin)
        Route::get('/leave-requests', [LeaveRequestController::class, 'adminIndex'])->name('admin.leave.requests');
        Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('admin.leave.requests.update');
    });
});


require __DIR__ . '/auth.php';