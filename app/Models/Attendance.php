<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'date', 'punch_in', 'punch_out', 'status', 'notes'];

    protected $casts = [
        'punch_in' => 'datetime',
        'punch_out' => 'datetime',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
