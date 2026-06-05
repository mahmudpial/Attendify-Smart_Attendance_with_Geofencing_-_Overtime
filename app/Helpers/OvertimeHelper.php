<?php

namespace App\Helpers;

use Carbon\Carbon;

class OvertimeHelper
{
    /**
     * Calculate normal and overtime hours for a given punch in/out.
     *
     * @param Carbon $punchIn
     * @param Carbon $punchOut
     * @return array ['normal_hours' => float, 'overtime_hours' => float, 'overtime_start' => Carbon|null]
     */
    public static function calculate($punchIn, $punchOut)
    {
        // Get work schedule from config with fallbacks
        $workStart = Carbon::parse(config('overtime.work_start', '09:00'));
        $workEnd = Carbon::parse(config('overtime.work_end', '18:00'));

        // Set dates to match punch in date
        $workStart->setDate($punchIn->year, $punchIn->month, $punchIn->day);
        $workEnd->setDate($punchIn->year, $punchIn->month, $punchIn->day);

        // If punch out is after midnight, cap at end of day (simplified)
        $endOfDay = Carbon::parse($punchIn->format('Y-m-d') . ' 23:59:59');
        $punchOut = $punchOut->min($endOfDay);

        // Total minutes worked
        $totalMinutes = $punchOut->diffInMinutes($punchIn);

        // Normal work period (intersection between punch interval and work hours)
        $normalStart = $punchIn->max($workStart);
        $normalEnd = $punchOut->min($workEnd);
        $normalMinutes = ($normalEnd > $normalStart) ? $normalEnd->diffInMinutes($normalStart) : 0;

        // Overtime = total - normal
        $overtimeMinutes = $totalMinutes - $normalMinutes;
        if ($overtimeMinutes < 0)
            $overtimeMinutes = 0;

        $normalHours = round($normalMinutes / 60, 2);
        $overtimeHours = round($overtimeMinutes / 60, 2);

        // Determine when overtime started (first minute after work_end)
        $overtimeStart = null;
        if ($overtimeHours > 0 && $punchOut > $workEnd) {
            $overtimeStart = $workEnd->copy();
        }

        return [
            'normal_hours' => $normalHours,
            'overtime_hours' => $overtimeHours,
            'overtime_start' => $overtimeStart,
        ];
    }

    /**
     * Calculate overtime pay based on multiplier thresholds.
     *
     * @param float $overtimeHours
     * @param float $baseHourlyRate
     * @return float
     */
    public static function calculatePay($overtimeHours, $baseHourlyRate = 10.0)
    {
        $rate1 = config('overtime.multiplier.rate1', 1.5);
        $rate2 = config('overtime.multiplier.rate2', 2.0);
        $threshold = config('overtime.multiplier.threshold_hours', 2);

        if ($overtimeHours <= $threshold) {
            $multiplied = $overtimeHours * $rate1;
        } else {
            $multiplied = $threshold * $rate1 + ($overtimeHours - $threshold) * $rate2;
        }
        return round($multiplied * $baseHourlyRate, 2);
    }
}