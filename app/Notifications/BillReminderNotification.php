<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillReminderNotification extends Notification
{
    use Queueable;

    public $bill;

    /**
     * Create a new notification instance.
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $daysUntilDue = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($this->bill->due_date)->startOfDay(), false);
        return [
            'message' => 'Tagihan ' . $this->bill->title . ' jatuh tempo dalam ' . $daysUntilDue . ' hari!',
            'bill_id' => $this->bill->id,
            'amount' => $this->bill->amount,
            'due_date' => $this->bill->due_date,
        ];
    }
}
