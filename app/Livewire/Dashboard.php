<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        
        $totalBalance = $user->wallets()->sum('balance');
        $incomeThisMonth = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->where('title', 'not like', 'Transfer dari %')
            ->sum('amount');
        $expenseThisMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->where('title', 'not like', 'Transfer ke %')
            ->sum('amount');
        
        $recentTransactions = $user->transactions()
            ->latest('transaction_date')
            ->take(5)
            ->get();
            
        $activeGoals = $user->goals()->take(2)->get();
        $upcomingBills = $user->bills()->where('status', 'Pending')->orderBy('due_date')->take(2)->get();

        return view('livewire.dashboard', [
            'totalBalance' => $totalBalance,
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'recentTransactions' => $recentTransactions,
            'activeGoals' => $activeGoals,
            'upcomingBills' => $upcomingBills
        ]);
    }
}
