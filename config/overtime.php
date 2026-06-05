<?php

return [
    'work_start' => env('WORK_START_TIME', '09:00'),
    'work_end' => env('WORK_END_TIME', '18:00'),
    'multiplier' => [
        'rate1' => (float) env('OVERTIME_MULTIPLIER_1', 1.5),
        'rate2' => (float) env('OVERTIME_MULTIPLIER_2', 2.0),
        'threshold_hours' => (float) env('OVERTIME_THRESHOLD_HOURS', 2),
    ],
];