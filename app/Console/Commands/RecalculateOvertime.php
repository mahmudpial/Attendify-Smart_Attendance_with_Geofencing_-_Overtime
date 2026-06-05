<?php

namespace App\Console\Commands;

use App\Helpers\OvertimeHelper;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculateOvertime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-overtime {--dry-run : Calculate without saving} {--date= : Recalculate only for specific date (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate normal and overtime hours for all attendance records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Attendance::whereNotNull('punch_in')
            ->whereNotNull('punch_out');

        if ($date = $this->option('date')) {
            $query->whereDate('date', $date);
            $this->info("Recalculating for date: {$date}");
        }

        $attendances = $query->get();
        $total = $attendances->count();
        $updated = 0;
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn("Dry run mode – no changes will be saved.");
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($attendances as $attendance) {
            $punchIn = Carbon::parse($attendance->punch_in);
            $punchOut = Carbon::parse($attendance->punch_out);
            $calc = OvertimeHelper::calculate($punchIn, $punchOut);

            if (!$dryRun) {
                $attendance->normal_hours = $calc['normal_hours'];
                $attendance->overtime_hours = $calc['overtime_hours'];
                $attendance->overtime_start = $calc['overtime_start'];
                $attendance->save();
            }
            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if ($dryRun) {
            $this->info("Dry run completed. {$updated} records would be updated.");
        } else {
            $this->info("Recalculation completed. {$updated} attendance records updated.");
        }
    }
}