<?php

namespace App\Livewire;

use Livewire\Component;

class Statistik extends Component
{
    public $trendType = 'expense';

    public function render()
    {
        $user = auth()->user();
        
        $incomeThisMonth = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');
            
        $expenseThisMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $biggestExpense = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->selectRaw('category as name, SUM(amount) as value')
            ->groupBy('category')
            ->orderByDesc('value')
            ->first();

        return view('livewire.statistik', [
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'biggestExpense' => $biggestExpense ? $biggestExpense->name : 'Belum Ada',
            'biggestExpenseValue' => $biggestExpense ? $biggestExpense->value : 0,
        ]);
    }
}
