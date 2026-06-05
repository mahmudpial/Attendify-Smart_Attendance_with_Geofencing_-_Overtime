<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Notifications\LeaveStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaveRequestController extends Controller
{
    // Employee: show leave request form & history
    public function index()
    {
        $user = Auth::user();
        $leaveRequests = $user->leaveRequests()->orderBy('created_at', 'desc')->paginate(10);
        $balances = [
            'annual' => $user->annual_leave_balance,
            'sick' => $user->sick_leave_balance,
            'casual' => $user->casual_leave_balance,
        ];
        return Inertia::render('Leave/MyLeaveRequests', [
            'leaveRequests' => $leaveRequests,
            'balances' => $balances,
        ]);
    }

    // Employee: submit leave request
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sick,casual,annual',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5',
        ]);

        $user = Auth::user();
        $days = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
        $balanceField = $request->type . '_leave_balance';

        if ($user->$balanceField < $days) {
            return back()->with('error', 'Insufficient leave balance.');
        }

        // Check for overlapping approved leave requests
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($overlap) {
            return back()->with('error', 'You already have approved leave in this period.');
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leave.requests')->with('success', 'Leave request submitted.');
    }

    // Admin: list all pending/approved/rejected leave requests
    public function adminIndex(Request $request)
    {
        $query = LeaveRequest::with(['user', 'approver']);
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(20);
        return Inertia::render('Admin/LeaveRequests', [
            'leaveRequests' => $leaveRequests,
            'statusFilter' => $request->status,
        ]);
    }

    // Admin: approve/reject leave request
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string',
        ]);

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $user = $leaveRequest->user;
        $days = $this->getDaysCount($leaveRequest->start_date, $leaveRequest->end_date);

        if ($request->status === 'approved') {
            // Check balance again
            $balanceField = $leaveRequest->type . '_leave_balance';
            if ($user->$balanceField < $days) {
                return back()->with('error', 'Insufficient leave balance now. Cannot approve.');
            }
            // Deduct balance using the helper method (must exist in User model)
            $user->deductLeaveBalance($leaveRequest->type, $days);

            // Mark attendance for each date in range as 'on_leave'
            $start = Carbon::parse($leaveRequest->start_date);
            $end = Carbon::parse($leaveRequest->end_date);
            for ($date = $start; $date->lte($end); $date->addDay()) {
                Attendance::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date->toDateString()],
                    [
                        'status' => 'on_leave',
                        'leave_request_id' => $leaveRequest->id,
                        'punch_in' => null,
                        'punch_out' => null,
                        'normal_hours' => 0,
                        'overtime_hours' => 0,
                    ]
                );
            }
        }

        $leaveRequest->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Send notification to employee
        $user->notify(new LeaveStatusNotification(
            $request->status,
            $leaveRequest->type,
            $request->admin_comment
        ));

        $message = $request->status === 'approved' ? 'Leave approved and balance deducted.' : 'Leave rejected.';
        return back()->with('success', $message);
    }

    // Helper method to calculate number of days between two dates
    private function getDaysCount($startDate, $endDate)
    {
        return Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
    }
}