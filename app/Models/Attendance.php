<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'punch_in',
        'punch_out',
        'status',
        'notes',
        'normal_hours',
        'overtime_hours',
        'overtime_start',
        'punch_in_lat',
        'punch_in_lng',
        'punch_in_ip',
        'punch_out_lat',
        'punch_out_lng',
        'punch_out_ip',
        'leave_request_id',
    ];

    protected $casts = [
        'punch_in' => 'datetime',
        'punch_out' => 'datetime',
        'date' => 'date',
        'overtime_start' => 'datetime',
        'normal_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'punch_in_lat' => 'decimal:7',
        'punch_in_lng' => 'decimal:7',
        'punch_out_lat' => 'decimal:7',
        'punch_out_lng' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }
}