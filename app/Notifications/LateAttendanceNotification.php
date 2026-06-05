<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LateAttendanceNotification extends Notification
{
    use Queueable;

    protected $employeeName;
    protected $punchInTime;

    public function __construct($employeeName, $punchInTime)
    {
        $this->employeeName = $employeeName;
        $this->punchInTime = $punchInTime;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Late Attendance Alert')
            ->line("Employee {$this->employeeName} punched in late at {$this->punchInTime}.")
            ->action('View Attendance', url('/admin/attendances'))
            ->line('Please review attendance records.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Late Punch In',
            'message' => "{$this->employeeName} punched in late at {$this->punchInTime}.",
        ];
    }
}