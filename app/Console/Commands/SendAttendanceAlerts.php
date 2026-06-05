<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Attendance;
use App\Notifications\PunchReminderNotification;
use App\Notifications\LateAttendanceNotification;
use App\Notifications\AbsentNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendAttendanceAlerts extends Command
{
    protected $signature = 'attendance:alerts';
    protected $description = 'Send daily punch reminders and late/absent alerts';

    public function handle()
    {
        $today = Carbon::today();
        $now = Carbon::now();

        // 1. Punch Reminder for employees who haven't punched in by 10:00 AM
        $reminderHour = 10;
        if ($now->hour == $reminderHour && $now->minute == 0) {
            $usersNotPunched = User::where('role', 'employee')
                ->whereDoesntHave('attendances', function ($q) use ($today) {
                    $q->whereDate('date', $today);
                })->get();

            foreach ($usersNotPunched as $user) {
                $user->notify(new PunchReminderNotification());
            }
            $this->info("Sent punch reminders to {$usersNotPunched->count()} employees.");
        }

        // 2. Late alerts: after work start hour (e.g., 9:30), mark those who punched late
        $lateThreshold = config('overtime.work_start'); // 09:00
        $lateCutoff = Carbon::parse($lateThreshold)->addMinutes(30); // 09:30

        $latePunches = Attendance::with('user')
            ->whereDate('date', $today)
            ->whereNotNull('punch_in')
            ->whereTime('punch_in', '>', $lateCutoff->format('H:i:s'))
            ->get();

        $admins = User::where('role', 'admin')->get();

        foreach ($latePunches as $att) {
            foreach ($admins as $admin) {
                $admin->notify(new LateAttendanceNotification(
                    $att->user->name,
                    Carbon::parse($att->punch_in)->format('H:i')
                ));
            }
        }
        $this->info("Sent late alerts for {$latePunches->count()} employees.");

        // 3. Absent alerts: at end of day (e.g., 8 PM) list absent employees
        if ($now->hour == 20 && $now->minute == 0) {
            $absentUsers = User::where('role', 'employee')
                ->whereDoesntHave('attendances', function ($q) use ($today) {
                    $q->whereDate('date', $today);
                })->get();

            foreach ($absentUsers as $user) {
                foreach ($admins as $admin) {
                    $admin->notify(new AbsentNotification($user->name, $today->toDateString()));
                }
            }
            $this->info("Sent absence alerts for {$absentUsers->count()} employees.");
        }

        return 0;
    }
}