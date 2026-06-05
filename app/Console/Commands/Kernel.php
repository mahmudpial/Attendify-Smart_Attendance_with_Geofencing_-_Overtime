<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('attendance:alerts')->everyMinute(); // or hourly, but better to run every minute and check conditions inside
        $schedule->command('app:calculate-daily-overtime')->dailyAt('23:59'); // Run at the end of the day
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}