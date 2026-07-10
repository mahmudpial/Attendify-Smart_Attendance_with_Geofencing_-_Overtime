<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GeofenceHelper;
use App\Helpers\OvertimeHelper;  // <-- add this import
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function punchIn(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $geofencingOn = config('geofence.enabled');

        $request->validate([
            'latitude' => ($geofencingOn ? 'required' : 'nullable') . '|numeric|between:-90,90',
            'longitude' => ($geofencingOn ? 'required' : 'nullable') . '|numeric|between:-180,180',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if ($geofencingOn && !GeofenceHelper::isWithinGeofence($latitude, $longitude)) {
            return back()->with('error', 'You are outside the office premises. Punch-in not allowed.');
        }

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        if ($attendance->punch_in && !$attendance->punch_out) {
            return back()->with('error', 'Already punched in. Please punch out first.');
        }

        if ($attendance->punch_in && $attendance->punch_out) {
            return back()->with('error', 'Attendance already completed for today.');
        }

        $attendance->punch_in = now();
        $attendance->punch_in_lat = $latitude;
        $attendance->punch_in_lng = $longitude;
        $attendance->punch_in_ip = $request->ip();
        $attendance->status = 'present';
        $attendance->save();

        return back()->with('success', 'Punched in successfully.');
    }

    public function punchOut(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $geofencingOn = config('geofence.enabled');

        $request->validate([
            'latitude' => ($geofencingOn ? 'required' : 'nullable') . '|numeric|between:-90,90',
            'longitude' => ($geofencingOn ? 'required' : 'nullable') . '|numeric|between:-180,180',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if ($geofencingOn && !GeofenceHelper::isWithinGeofence($latitude, $longitude)) {
            return back()->with('error', 'You are outside the office premises. Punch-out not allowed.');
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->punch_in) {
            return back()->with('error', 'You must punch in first.');
        }

        if ($attendance->punch_out) {
            return back()->with('error', 'Already punched out today.');
        }

        $punchOutTime = now();
        $attendance->punch_out = $punchOutTime;
        $attendance->punch_out_lat = $latitude;
        $attendance->punch_out_lng = $longitude;
        $attendance->punch_out_ip = $request->ip();

        // ⭐ Calculate overtime using the helper
        $punchInTime = Carbon::parse($attendance->punch_in);
        $calc = OvertimeHelper::calculate($punchInTime, $punchOutTime);
        $attendance->normal_hours = $calc['normal_hours'];
        $attendance->overtime_hours = $calc['overtime_hours'];
        $attendance->overtime_start = $calc['overtime_start'];

        $attendance->save();

        $message = 'Punched out successfully.';
        if ($calc['overtime_hours'] > 0) {
            $message .= " Overtime: {$calc['overtime_hours']} hours.";
        }
        return back()->with('success', $message);
    }

    // Employee view their attendance history
    public function myAttendance(Request $request)
    {
        $attendances = Auth::user()->attendances()
            ->orderBy('date', 'desc')
            ->paginate(15);

        return Inertia::render('MyAttendance', ['attendances' => $attendances]);
    }

    // Admin: View all attendances with filters
    public function adminIndex(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);
        $users = \App\Models\User::all();

        return Inertia::render('Admin/Attendances', [
            'attendances' => $attendances,
            'users' => $users,
            'filters' => $request->only(['user_id', 'start_date', 'end_date']),
        ]);
    }
}