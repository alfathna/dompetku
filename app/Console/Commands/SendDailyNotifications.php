<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-daily-notifications')]
#[Description('Command description')]
class SendDailyNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Budgets (>= 80%)
        $budgets = \App\Models\Budget::whereDate('end_date', '>=', now()->toDateString())->get();
        foreach($budgets as $budget) {
            if ($budget->amount > 0) {
                $percentage = ($budget->spent_amount / $budget->amount) * 100;
                if ($percentage >= 80) {
                    $budget->user->notifications()->create([
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'type' => 'App\Notifications\BudgetLimitNotification',
                        'data' => [
                            'message' => 'Budget ' . $budget->category . ' hampir habis!',
                            'budget_id' => $budget->id,
                            'percentage' => round($percentage),
                            'usedAmount' => $budget->spent_amount,
                        ],
                        'read_at' => null,
                    ]);
                }
            }
        }

        // 2. Bills (has_reminder == true, <= 3 days, not Paid)
        $bills = \App\Models\Bill::where('status', '!=', 'Paid')->where('has_reminder', true)->get();
        foreach($bills as $bill) {
            $daysUntilDue = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($bill->due_date)->startOfDay(), false);
            if ($daysUntilDue >= 0 && $daysUntilDue <= 3) {
                $bill->user->notifications()->create([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => 'App\Notifications\BillReminderNotification',
                    'data' => [
                        'message' => 'Tagihan ' . $bill->title . ' jatuh tempo dalam ' . $daysUntilDue . ' hari!',
                        'bill_id' => $bill->id,
                        'amount' => $bill->amount,
                        'due_date' => $bill->due_date,
                    ],
                    'read_at' => null,
                ]);
            }
        }
        
        $this->info('Daily notifications sent.');
    }
}
