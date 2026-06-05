<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        // If admin, render admin dashboard with recent attendances
        if ($user->role === 'admin') {
            $recentAttendances = Attendance::with('user')
                ->orderBy('date', 'desc')
                ->limit(10)
                ->get();
            return Inertia::render('Admin/Dashboard', [
                'recentAttendances' => $recentAttendances,
            ]);
        }

        // Employee dashboard: show today's attendance and punch buttons
        $today = Carbon::today();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // If no attendance record exists for today, create an empty model with default values
        // to avoid undefined property errors in the Vue component
        if (!$todayAttendance) {
            $todayAttendance = new Attendance();
            $todayAttendance->normal_hours = 0;
            $todayAttendance->overtime_hours = 0;
            // other fields will be null/empty, but Vue handles with optional chaining
        }

        return Inertia::render('Dashboard', [
            'todayAttendance' => $todayAttendance,
        ]);
    }
}