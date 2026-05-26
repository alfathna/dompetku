<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Statistik extends Component
{
    public $trendType = 'expense';

    public function render()
    {
        $user = auth()->user();
        $now = Carbon::now();
        
        $incomeThisMonth = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('title', 'not like', 'Transfer dari %')
            ->sum('amount');
            
        $expenseThisMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('title', 'not like', 'Transfer ke %')
            ->sum('amount');

        $lastMonth = $now->copy()->subMonth();
        $incomeLastMonth = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', $lastMonth->month)
            ->whereYear('transaction_date', $lastMonth->year)
            ->where('title', 'not like', 'Transfer dari %')
            ->sum('amount');

        $expenseLastMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $lastMonth->month)
            ->whereYear('transaction_date', $lastMonth->year)
            ->where('title', 'not like', 'Transfer ke %')
            ->sum('amount');

        // Hari Paling Boros (Day with highest expense this month)
        $mostWastefulDayData = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('title', 'not like', 'Transfer ke %')
            ->selectRaw('DATE(transaction_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderByDesc('total')
            ->first();

        // Chart Data - Donut
        $categoryData = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('title', 'not like', 'Transfer ke %')
            ->selectRaw('category as name, SUM(amount) as value')
            ->groupBy('category')
            ->orderByDesc('value')
            ->get();

        // Daily Chart Data (This month)
        $daysInMonth = $now->daysInMonth;
        $dailyTransactions = $user->transactions()
            ->whereIn('type', ['income', 'expense'])
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->where('title', 'not like', 'Transfer %')
            ->selectRaw('DAY(transaction_date) as day, type, SUM(amount) as total')
            ->groupBy('day', 'type')
            ->get();
            
        $dailyLabels = [];
        $dailyExpense = [];
        $dailyIncome = [];
        $dailyNet = [];
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dailyLabels[] = $i;
            $exp = $dailyTransactions->where('day', $i)->where('type', 'expense')->first()->total ?? 0;
            $inc = $dailyTransactions->where('day', $i)->where('type', 'income')->first()->total ?? 0;
            $dailyExpense[] = (float) $exp;
            $dailyIncome[] = (float) $inc;
            $dailyNet[] = (float) ($inc - $exp);
        }

        // Monthly Chart Data (Last 6 months)
        $monthlyLabels = [];
        $monthlyExpense = [];
        $monthlyIncome = [];
        $monthlyNet = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $monthlyLabels[] = $m->translatedFormat('M');
            
            $exp = $user->transactions()
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $m->month)
                ->whereYear('transaction_date', $m->year)
                ->where('title', 'not like', 'Transfer ke %')
                ->sum('amount');
                
            $inc = $user->transactions()
                ->where('type', 'income')
                ->whereMonth('transaction_date', $m->month)
                ->whereYear('transaction_date', $m->year)
                ->where('title', 'not like', 'Transfer dari %')
                ->sum('amount');
                
            $monthlyExpense[] = (float) $exp;
            $monthlyIncome[] = (float) $inc;
            $monthlyNet[] = (float) ($inc - $exp);
        }

        return view('livewire.statistik', [
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'incomeLastMonth' => $incomeLastMonth,
            'expenseLastMonth' => $expenseLastMonth,
            'mostWastefulDay' => $mostWastefulDayData,
            'categoryData' => $categoryData,
            'dailyData' => [
                'labels' => $dailyLabels,
                'expense' => $dailyExpense,
                'income' => $dailyIncome,
                'net' => $dailyNet,
            ],
            'monthlyData' => [
                'labels' => $monthlyLabels,
                'expense' => $monthlyExpense,
                'income' => $monthlyIncome,
                'net' => $monthlyNet,
            ],
            'budgets' => $user->budgets,
        ]);
    }
}
