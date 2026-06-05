<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PunchReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reminder: Please Punch In')
            ->line('You have not punched in for today yet.')
            ->action('Go to Dashboard', url('/dashboard'))
            ->line('Please remember to punch in and out to track your attendance.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Punch Reminder',
            'message' => 'You have not punched in today yet. Please do so now.',
        ];
    }
}