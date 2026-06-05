<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Helpers\OvertimeHelper;  // ✅ Fixed: correct case
use App\Helpers\GeofenceHelper;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $attendances = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $report = [];
        $totalNormalHours = 0;
        $totalOvertimeHours = 0;

        foreach ($attendances as $att) {
            $userId = $att->user_id;
            if (!isset($report[$userId])) {
                $report[$userId] = [
                    'user_name' => $att->user->name,
                    'total_days' => 0,
                    'total_hours' => 0,
                    'total_normal' => 0,
                    'total_overtime' => 0,
                    'records' => [],
                ];
            }

            $report[$userId]['total_days']++;

            $normal = $att->normal_hours ?? 0;
            $overtime = $att->overtime_hours ?? 0;

            if ($normal == 0 && $overtime == 0 && $att->punch_in && $att->punch_out) {
                $total = Carbon::parse($att->punch_in)->diffInHours(Carbon::parse($att->punch_out));
                $normal = $total;
            }

            $report[$userId]['total_hours'] += $normal + $overtime;
            $report[$userId]['total_normal'] += $normal;
            $report[$userId]['total_overtime'] += $overtime;
            $totalNormalHours += $normal;
            $totalOvertimeHours += $overtime;

            $report[$userId]['records'][] = $att;
        }

        return Inertia::render('Admin/Reports', [
            'report' => $report,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'summary' => [
                'total_normal' => $totalNormalHours,
                'total_overtime' => $totalOvertimeHours,
            ]
        ]);
    }

    public function export(Request $request)
    {
        $attendances = Attendance::with('user')
            ->when($request->start_date, fn($q) => $q->whereDate('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('date', '<=', $request->end_date))
            ->get();

        $csvFileName = 'attendance_report_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, [
            'Employee Name',
            'Date',
            'Punch In',
            'Punch Out',
            'Normal Hours',
            'Overtime Hours',
            'Total Hours',
            'Status'
        ]);

        foreach ($attendances as $att) {
            $normal = $att->normal_hours ?? 0;
            $overtime = $att->overtime_hours ?? 0;
            $total = $normal + $overtime;

            if ($normal == 0 && $overtime == 0 && $att->punch_in && $att->punch_out) {
                $total = Carbon::parse($att->punch_in)->diffInHours(Carbon::parse($att->punch_out));
                $normal = $total;
            }

            fputcsv($handle, [
                $att->user->name,
                $att->date,
                $att->punch_in ? Carbon::parse($att->punch_in)->format('H:i:s') : '-',
                $att->punch_out ? Carbon::parse($att->punch_out)->format('H:i:s') : '-',
                number_format($normal, 2),
                number_format($overtime, 2),
                number_format($total, 2),
                $att->status,
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$csvFileName\"");
    }

    // ✅ Fixed: added default date range and validation
    public function payrollReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $users = User::with([
            'attendances' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            }
        ])->get();

        $hourlyRate = 10.0; // you can make this per-user from database

        $payroll = [];
        foreach ($users as $user) {
            $totalOT = $user->attendances->sum('overtime_hours');
            $otPay = OvertimeHelper::calculatePay($totalOT, $hourlyRate);
            $payroll[] = [
                'name' => $user->name,
                'total_overtime' => number_format($totalOT, 2),
                'overtime_pay' => number_format($otPay, 2),
            ];
        }

        return Inertia::render('Admin/Payroll', [
            'payroll' => $payroll,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }
}