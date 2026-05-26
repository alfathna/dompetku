<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class Keuangan extends Component
{
    use WithPagination;

    public $activeTab = 'transaksi';

    // Transactions Filters
    public $searchTx = '';
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $sortNominal = 'desc';
    public $filterType = '';

    public function updatingSearchTx() { $this->resetPage(); }
    public function updatingFilterStartDate() { $this->resetPage(); }
    public function updatingFilterEndDate() { $this->resetPage(); }
    public function updatingSortNominal() { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['searchTx', 'filterStartDate', 'filterEndDate', 'sortNominal', 'filterType']);
        $this->resetPage();
    }

    
    // Wallets
    public $showAddWalletModal = false;
    public $walletName;
    public $walletType = '';
    public $walletBalance;

    public $showEditWalletModal = false;
    public $editWalletId;
    public $editWalletName;
    public $editWalletType;
    public $editWalletBalance;

    // Transactions
    public $showAddModal = false;
    public $txName;
    public $txType = 'expense';
    public $txAmount;
    public $txWallet;
    public $txCategory;
    public $txDate;
    public $txNotes;

    // Budgets
    public $showAddBudgetModal = false;
    public $showEditBudgetModal = false;
    public $budgetId;
    public $budgetCategory = '';
    public $budgetPeriod = 'monthly';
    public $budgetLimit;
    public $budgetNotify = false;
    public $budgetNote;

    // Goals
    public $showAddGoalModal = false;
    public $showManageGoalModal = false;
    public $goalId;
    public $goalName;
    public $goalTargetAmount;
    public $goalEstimateDate;
    public $goalMonthlyCapacity;
    
    // Add savings to Goal
    public $goalAddAmount;
    public $goalAddWallet;
    public $goalAddDate;
    public $goalAddNotes;

    // Bills Filter
    public $filterBill = 'Semua';

    // Transfer State
    public $showTransferModal = false;
    public $transferFromId = '';
    public $transferToId = '';
    public $transferAmount;
    public $transferNote = '';

    // Topup State
    public $showTopupModal = false;
    public $topupAmount;

    public $showAddBillModal = false;
    public $showEditBillModal = false;
    public $showPayConfirmModal = false;
    public $showDeleteConfirmModal = false;
    
    // Pay Confirm State
    public $payBillId;
    public $payBillTitle;
    public $payBillAmount;
    public $payBillIcon;
    
    // Delete Confirm State
    public $deleteId;
    public $deleteType;
    public $deleteTitle;
    
    public $editBillId;
    public $billName;
    public $billCategory = 'Listrik';
    public $billWallet = '';
    public $billAmount;
    public $billDate;
    public $billReminder = false;
    public $billRepeat = false;
    public $billNote = '';

    public function saveWallet()
    {
        $this->validate([
            'walletName' => 'required|string|max:255',
            'walletType' => 'required|string|max:255',
            'walletBalance' => 'nullable|numeric',
        ]);

        auth()->user()->wallets()->create([
            'name' => $this->walletName,
            'type' => $this->walletType,
            'balance' => $this->walletBalance ?? 0,
        ]);

        $this->reset(['walletName', 'walletType', 'walletBalance', 'showAddWalletModal']);
    }

    public function editWallet($id)
    {
        $wallet = auth()->user()->wallets()->findOrFail($id);
        $this->editWalletId = $wallet->id;
        $this->editWalletName = $wallet->name;
        $this->editWalletType = $wallet->type;
        $this->editWalletBalance = $wallet->balance;
        $this->showEditWalletModal = true;
    }

    public function executeTopup()
    {
        $this->validate([
            'topupAmount' => 'required|numeric|min:1',
        ]);

        $wallet = auth()->user()->wallets()->findOrFail($this->editWalletId);
        
        auth()->user()->transactions()->create([
            'title' => 'Top Up Saldo',
            'type' => 'income',
            'amount' => $this->topupAmount,
            'wallet_id' => $wallet->id,
            'category' => 'Lainnya',
            'transaction_date' => now(),
            'notes' => 'Top up dari edit wallet',
        ]);

        $wallet->balance += $this->topupAmount;
        $wallet->save();

        $this->editWalletBalance = $wallet->balance;
        $this->reset(['showTopupModal', 'topupAmount']);
    }

    public function updateWallet()
    {
        $this->validate([
            'editWalletName' => 'required|string|max:255',
            'editWalletType' => 'required|string|max:255',
        ]);

        $wallet = auth()->user()->wallets()->findOrFail($this->editWalletId);
        $wallet->update([
            'name' => $this->editWalletName,
            'type' => $this->editWalletType,
        ]);

        $this->reset(['editWalletId', 'editWalletName', 'editWalletType', 'editWalletBalance', 'showEditWalletModal']);
    }

    public function deleteWallet($id)
    {
        $wallet = auth()->user()->wallets()->findOrFail($id);
        // Note: Transactions related to this wallet might need cascading delete or set to null based on DB constraints.
        // Assuming cascade on delete is set in migration.
        $wallet->delete();
    }

    public function executeTransfer()
    {
        $this->validate([
            'transferFromId' => 'required|exists:wallets,id',
            'transferToId' => 'required|exists:wallets,id|different:transferFromId',
            'transferAmount' => 'required|numeric|min:1',
        ], [
            'transferToId.different' => 'Wallet tujuan harus berbeda dari wallet asal.'
        ]);

        $fromWallet = auth()->user()->wallets()->findOrFail($this->transferFromId);
        $toWallet = auth()->user()->wallets()->findOrFail($this->transferToId);

        if ($fromWallet->balance < $this->transferAmount) {
            $this->addError('transferAmount', 'Saldo tidak mencukupi.');
            return;
        }

        // Expense from source
        auth()->user()->transactions()->create([
            'title' => 'Transfer ke ' . $toWallet->name,
            'type' => 'expense',
            'amount' => $this->transferAmount,
            'wallet_id' => $fromWallet->id,
            'category' => 'Lainnya',
            'transaction_date' => now(),
            'notes' => $this->transferNote,
        ]);

        // Income to dest
        auth()->user()->transactions()->create([
            'title' => 'Transfer dari ' . $fromWallet->name,
            'type' => 'income',
            'amount' => $this->transferAmount,
            'wallet_id' => $toWallet->id,
            'category' => 'Lainnya',
            'transaction_date' => now(),
            'notes' => $this->transferNote,
        ]);

        $fromWallet->balance -= $this->transferAmount;
        $fromWallet->save();

        $toWallet->balance += $this->transferAmount;
        $toWallet->save();

        $this->reset(['showTransferModal', 'transferFromId', 'transferToId', 'transferAmount', 'transferNote']);
    }

    public function saveTransaction()
    {
        $this->validate([
            'txName' => 'required|string|max:255',
            'txType' => 'required|in:income,expense',
            'txAmount' => 'required|numeric',
            'txWallet' => 'required|exists:wallets,id',
            'txCategory' => 'required|string',
            'txDate' => 'required|date',
            'txNotes' => 'nullable|string',
        ], [
            'txCategory.required' => 'Pilih kategori transaksi agar dapat dihitung pada Budget.',
        ]);

        $tx = auth()->user()->transactions()->create([
            'title' => $this->txName,
            'type' => $this->txType,
            'amount' => $this->txAmount,
            'wallet_id' => $this->txWallet,
            'category' => $this->txCategory,
            'transaction_date' => $this->txDate,
            'notes' => $this->txNotes,
        ]);

        // Update Wallet Balance
        $wallet = \App\Models\Wallet::find($this->txWallet);
        if ($this->txType === 'income') {
            $wallet->balance += $this->txAmount;
        } else {
            $wallet->balance -= $this->txAmount;
        }
        $wallet->save();

        // Check Budget Limit
        if ($this->txType === 'expense') {
            $budget = auth()->user()->budgets()->where('category', $this->txCategory)->first();
            if ($budget && $budget->alert_threshold > 0) {
                $now = \Carbon\Carbon::now();
                if ($budget->period === 'weekly') {
                    $startDate = $now->copy()->startOfWeek();
                    $endDate = $now->copy()->endOfWeek();
                } else {
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                }

                $used = \App\Models\Transaction::where('user_id', auth()->id())
                    ->where('type', 'expense')
                    ->where('category', $budget->category)
                    ->whereBetween('transaction_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->sum('amount');

                if ($budget->limit_amount > 0) {
                    $percentage = ($used / $budget->limit_amount) * 100;
                    
                    if ($percentage >= $budget->alert_threshold) {
                        $alreadyNotified = auth()->user()->unreadNotifications()
                            ->where('type', \App\Notifications\BudgetLimitNotification::class)
                            ->where('data->category', $budget->category)
                            ->exists();

                        if (!$alreadyNotified) {
                            auth()->user()->notify(new \App\Notifications\BudgetLimitNotification(
                                $budget->category,
                                $percentage >= 100 ? 100 : round($percentage),
                                $used,
                                $budget->limit_amount
                            ));
                        }
                    }
                }
            }
        }

        $this->reset(['txName', 'txAmount', 'txCategory', 'txNotes', 'showAddModal']);
        $this->txType = 'expense';
        $this->txDate = null;
    }

    public function saveBudget()
    {
        $this->validate([
            'budgetCategory' => 'required|string|max:255',
            'budgetPeriod' => 'required|string|in:monthly,weekly',
            'budgetLimit' => 'required|numeric',
        ], [
            'budgetCategory.required' => 'Kategori wajib dipilih.',
        ]);

        // Cek apakah kategori sudah ada
        $exists = auth()->user()->budgets()->where('category', $this->budgetCategory)->exists();
        if ($exists) {
            $this->addError('budgetCategory', 'Budget untuk kategori ini sudah ada.');
            return;
        }

        auth()->user()->budgets()->create([
            'title' => $this->budgetCategory,
            'category' => $this->budgetCategory,
            'period' => $this->budgetPeriod,
            'limit_amount' => $this->budgetLimit,
            'alert_threshold' => $this->budgetNotify ? 80 : 0,
            'note' => $this->budgetNote,
        ]);

        $this->reset(['budgetCategory', 'budgetPeriod', 'budgetLimit', 'budgetNotify', 'budgetNote', 'showAddBudgetModal']);
    }

    public function editBudget($id)
    {
        $budget = auth()->user()->budgets()->findOrFail($id);
        $this->budgetId = $budget->id;
        $this->budgetCategory = $budget->category;
        $this->budgetPeriod = $budget->period;
        $this->budgetLimit = $budget->limit_amount;
        $this->budgetNotify = $budget->alert_threshold == 80;
        $this->budgetNote = $budget->note;
        
        $this->showEditBudgetModal = true;
    }

    public function updateBudget()
    {
        $this->validate([
            'budgetPeriod' => 'required|string|in:monthly,weekly',
            'budgetLimit' => 'required|numeric',
        ]);

        $budget = auth()->user()->budgets()->findOrFail($this->budgetId);
        $budget->update([
            'period' => $this->budgetPeriod,
            'limit_amount' => $this->budgetLimit,
            'alert_threshold' => $this->budgetNotify ? 80 : 0,
            'note' => $this->budgetNote,
        ]);

        $this->reset(['budgetId', 'budgetCategory', 'budgetPeriod', 'budgetLimit', 'budgetNotify', 'budgetNote', 'showEditBudgetModal']);
    }

    public function deleteBudget($id)
    {
        auth()->user()->budgets()->findOrFail($id)->delete();
    }

    public function saveBill()
    {
        $this->validate([
            'billName' => 'required|string|max:255',
            'billAmount' => 'required|numeric',
            'billDate' => 'required|date',
            'billCategory' => 'required|string',
            'billWallet' => 'required|exists:wallets,id',
        ]);

        $categoryIconMap = [
            'Listrik' => 'zap',
            'Air' => 'droplets',
            'Internet' => 'wifi',
            'Cicilan' => 'credit-card',
            'Langganan' => 'repeat',
            'Sewa' => 'home',
            'Asuransi' => 'shield',
            'Lainnya' => 'tag',
        ];

        $bill = auth()->user()->bills()->create([
            'title' => $this->billName,
            'amount' => $this->billAmount,
            'due_date' => $this->billDate,
            'icon' => $categoryIconMap[$this->billCategory] ?? 'tag',
            'wallet_id' => $this->billWallet,
            'has_reminder' => $this->billReminder,
            'is_recurring' => $this->billRepeat,
        ]);

        $this->checkAndSendReminder($bill);

        $this->reset(['billName', 'billCategory', 'billWallet', 'billAmount', 'billDate', 'billReminder', 'billRepeat', 'billNote', 'showAddBillModal']);
    }

    public function confirmPayBill($id)
    {
        $bill = auth()->user()->bills()->findOrFail($id);
        $this->payBillId = $bill->id;
        $this->payBillTitle = $bill->title;
        $this->payBillAmount = $bill->amount;
        $this->payBillIcon = $bill->icon ?: 'smartphone';
        $this->showPayConfirmModal = true;
    }

    public function payBill($billId)
    {
        $bill = auth()->user()->bills()->findOrFail($billId);
        
        if ($bill->status === 'Paid') return;

        // Deduct from wallet if wallet exists
        if ($bill->wallet_id) {
            $wallet = \App\Models\Wallet::find($bill->wallet_id);
            if ($wallet) {
                // Create transaction
                auth()->user()->transactions()->create([
                    'title' => 'Bayar Tagihan: ' . $bill->title,
                    'type' => 'expense',
                    'amount' => $bill->amount,
                    'wallet_id' => $wallet->id,
                    'category' => 'Tagihan',
                    'transaction_date' => now(),
                    'notes' => 'Pembayaran tagihan jatuh tempo ' . $bill->due_date,
                ]);

                // Deduct balance
                $wallet->balance -= $bill->amount;
                $wallet->save();
            }
        }

        $bill->update(['status' => 'Paid']);

        if ($bill->is_recurring) {
            auth()->user()->bills()->create([
                'title' => $bill->title,
                'amount' => $bill->amount,
                'due_date' => \Carbon\Carbon::parse($bill->due_date)->addMonth()->format('Y-m-d'),
                'icon' => $bill->icon,
                'wallet_id' => $bill->wallet_id,
                'has_reminder' => $bill->has_reminder,
                'is_recurring' => $bill->is_recurring,
            ]);
        }

        $this->showPayConfirmModal = false;
    }

    public function confirmDelete($type, $id, $title)
    {
        $this->deleteType = $type;
        $this->deleteId = $id;
        $this->deleteTitle = $title;
        $this->showDeleteConfirmModal = true;
    }

    public function executeDelete()
    {
        if ($this->deleteType === 'transaction') {
            $this->deleteTransaction($this->deleteId);
        } elseif ($this->deleteType === 'wallet') {
            $this->deleteWallet($this->deleteId);
        } elseif ($this->deleteType === 'budget') {
            $this->deleteBudget($this->deleteId);
        } elseif ($this->deleteType === 'bill') {
            $this->deleteBill($this->deleteId);
        } elseif ($this->deleteType === 'goal') {
            $this->deleteGoal($this->deleteId);
        }
        
        $this->showDeleteConfirmModal = false;
        $this->deleteId = null;
        $this->deleteType = '';
    }

    public function executePayBill()
    {
        if ($this->payBillId) {
            $bill = auth()->user()->bills()->findOrFail($this->payBillId);
            
            if ($bill->status === 'Paid') {
                $this->showPayConfirmModal = false;
                $this->payBillId = null;
                return;
            }

            // Deduct from wallet if wallet exists
            if ($bill->wallet_id) {
                $wallet = \App\Models\Wallet::find($bill->wallet_id);
                if ($wallet) {
                    if ($wallet->balance < $bill->amount) {
                        $this->addError('payError', 'Saldo wallet tidak mencukupi (Sisa: Rp ' . number_format($wallet->balance, 0, ',', '.') . '). Silakan ubah tagihan ke wallet lain atau top up.');
                        return;
                    }

                    // Create transaction
                    auth()->user()->transactions()->create([
                        'title' => 'Bayar Tagihan: ' . $bill->title,
                        'type' => 'expense',
                        'amount' => $bill->amount,
                        'wallet_id' => $wallet->id,
                        'category' => 'Tagihan',
                        'transaction_date' => now(),
                        'notes' => 'Pembayaran tagihan jatuh tempo ' . $bill->due_date,
                    ]);

                    // Deduct balance
                    $wallet->balance -= $bill->amount;
                    $wallet->save();
                }
            }

            $bill->update(['status' => 'Paid']);
            
            if ($bill->is_recurring) {
                auth()->user()->bills()->create([
                    'title' => $bill->title,
                    'amount' => $bill->amount,
                    'due_date' => \Carbon\Carbon::parse($bill->due_date)->addMonth()->format('Y-m-d'),
                    'icon' => $bill->icon,
                    'wallet_id' => $bill->wallet_id,
                    'has_reminder' => $bill->has_reminder,
                    'is_recurring' => $bill->is_recurring,
                ]);
            }

            $this->showPayConfirmModal = false;
            $this->payBillId = null;
        }
    }

    public function deleteBill($id)
    {
        auth()->user()->bills()->findOrFail($id)->delete();
    }

    public function deleteGoal($id)
    {
        auth()->user()->goals()->findOrFail($id)->delete();
    }

    public function editBill($id)
    {
        $bill = auth()->user()->bills()->findOrFail($id);
        $this->editBillId = $bill->id;
        $this->billName = $bill->title;
        
        $iconCategoryMap = [
            'zap' => 'Listrik',
            'droplets' => 'Air',
            'wifi' => 'Internet',
            'credit-card' => 'Cicilan',
            'repeat' => 'Langganan',
            'home' => 'Sewa',
            'shield' => 'Asuransi',
            'tag' => 'Lainnya',
        ];
        $this->billCategory = $iconCategoryMap[$bill->icon] ?? 'Lainnya';
        
        $this->billWallet = $bill->wallet_id;
        $this->billAmount = $bill->amount;
        $this->billDate = $bill->due_date;
        $this->billReminder = $bill->has_reminder;
        $this->billRepeat = $bill->is_recurring;
        $this->showEditBillModal = true;
    }

    public function updateBill()
    {
        $this->validate([
            'billName' => 'required|string|max:255',
            'billAmount' => 'required|numeric',
            'billDate' => 'required|date',
            'billCategory' => 'required|string',
            'billWallet' => 'required|exists:wallets,id',
        ]);

        $categoryIconMap = [
            'Listrik' => 'zap',
            'Air' => 'droplets',
            'Internet' => 'wifi',
            'Cicilan' => 'credit-card',
            'Langganan' => 'repeat',
            'Sewa' => 'home',
            'Asuransi' => 'shield',
            'Lainnya' => 'tag',
        ];

        $bill = auth()->user()->bills()->findOrFail($this->editBillId);
        $bill->update([
            'title' => $this->billName,
            'amount' => $this->billAmount,
            'due_date' => $this->billDate,
            'icon' => $categoryIconMap[$this->billCategory] ?? 'tag',
            'wallet_id' => $this->billWallet,
            'has_reminder' => $this->billReminder,
            'is_recurring' => $this->billRepeat,
        ]);

        $this->checkAndSendReminder($bill);

        $this->reset(['editBillId', 'billName', 'billCategory', 'billWallet', 'billAmount', 'billDate', 'billReminder', 'billRepeat', 'showEditBillModal']);
    }

    // --- GOALS LOGIC ---
    public function saveGoal()
    {
        $this->validate([
            'goalName' => 'required|string|max:255',
            'goalTargetAmount' => 'required|numeric|min:1',
            'goalEstimateDate' => 'required|date',
            'goalMonthlyCapacity' => 'required|numeric|min:0',
        ]);

        if ($this->goalId) {
            $goal = auth()->user()->goals()->findOrFail($this->goalId);
            $goal->update([
                'title' => $this->goalName,
                'target_amount' => $this->goalTargetAmount,
                'estimate_date' => $this->goalEstimateDate,
                'monthly_capacity' => $this->goalMonthlyCapacity,
            ]);
        } else {
            auth()->user()->goals()->create([
                'title' => $this->goalName,
                'target_amount' => $this->goalTargetAmount,
                'estimate_date' => $this->goalEstimateDate,
                'monthly_capacity' => $this->goalMonthlyCapacity,
                'color' => 'emerald',
            ]);
        }

        $this->reset(['goalId', 'goalName', 'goalTargetAmount', 'goalEstimateDate', 'goalMonthlyCapacity', 'showAddGoalModal']);
    }

    public function editGoal($id)
    {
        $goal = auth()->user()->goals()->findOrFail($id);
        $this->goalId = $goal->id;
        $this->goalName = $goal->title;
        $this->goalTargetAmount = $goal->target_amount;
        $this->goalEstimateDate = $goal->estimate_date;
        $this->goalMonthlyCapacity = $goal->monthly_capacity;
        $this->showAddGoalModal = true;
    }

    public function manageGoal($id)
    {
        $goal = auth()->user()->goals()->findOrFail($id);
        $this->goalId = $goal->id;
        $this->goalName = $goal->title;
        $this->goalAddAmount = null;
        $this->goalAddWallet = '';
        $this->goalAddDate = now()->format('Y-m-d');
        $this->goalAddNotes = '';
        $this->showManageGoalModal = true;
    }

    public function saveGoalSavings()
    {
        $this->validate([
            'goalAddAmount' => 'required|numeric|min:1',
            'goalAddWallet' => 'required|exists:wallets,id',
            'goalAddDate' => 'required|date',
        ]);

        $wallet = \App\Models\Wallet::findOrFail($this->goalAddWallet);
        if ($wallet->balance < $this->goalAddAmount) {
            $this->addError('goalAddAmount', 'Saldo wallet tidak mencukupi.');
            return;
        }

        $goal = auth()->user()->goals()->findOrFail($this->goalId);

        // Record Transaction
        auth()->user()->transactions()->create([
            'title' => 'Setoran Tabungan: ' . $goal->title,
            'type' => 'expense',
            'amount' => $this->goalAddAmount,
            'wallet_id' => $wallet->id,
            'category' => 'Lainnya',
            'transaction_date' => $this->goalAddDate,
            'notes' => $this->goalAddNotes,
        ]);

        // Deduct from wallet
        $wallet->balance -= $this->goalAddAmount;
        $wallet->save();

        // Add to goal
        $goal->collected_amount += $this->goalAddAmount;
        $goal->save();

        $this->reset(['goalId', 'showManageGoalModal', 'goalAddAmount', 'goalAddWallet', 'goalAddDate', 'goalAddNotes']);
    }

    public function checkAndSendReminder($bill)
    {
        if ($bill->has_reminder && !$bill->reminder_sent && $bill->status !== 'Paid') {
            $daysUntilDue = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($bill->due_date)->startOfDay(), false);
            
            if ($daysUntilDue >= 0 && $daysUntilDue <= 3) {
                // Send Database Notification
                auth()->user()->notifications()->create([
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

                $bill->update(['reminder_sent' => true]);
                
                // Add flash or dispatch event for UI if needed
                $this->dispatch('notifications-updated');
            } elseif ($daysUntilDue < 0) {
                // Send Database Notification
                auth()->user()->notifications()->create([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => 'App\Notifications\BillReminderNotification',
                    'data' => [
                        'message' => 'Tagihan ' . $bill->title . ' telah melewati tanggal jatuh tempo (' . abs($daysUntilDue) . ' hari yang lalu)!',
                        'bill_id' => $bill->id,
                        'amount' => $bill->amount,
                        'due_date' => $bill->due_date,
                    ],
                    'read_at' => null,
                ]);

                $bill->update(['reminder_sent' => true]);
                
                // Add flash or dispatch event for UI if needed
                $this->dispatch('notifications-updated');
            }
        }
    }

    public function exportTransactions()
    {
        $transactions = auth()->user()->transactions()->orderBy('transaction_date', 'desc')->get();
        $csvFileName = 'transaksi_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Transaksi', 'Kategori', 'Tipe', 'Nominal', 'Wallet', 'Catatan']);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    \Carbon\Carbon::parse($tx->transaction_date)->format('Y-m-d'),
                    $tx->title,
                    $tx->category ?? '-',
                    $tx->type,
                    $tx->amount,
                    $tx->wallet ? $tx->wallet->name : '-',
                    $tx->notes ?? ''
                ]);
            }
            fclose($file);
        }, $csvFileName);
    }

    public function deleteTransaction($id)
    {
        $tx = auth()->user()->transactions()->findOrFail($id);
        
        // Reverse Wallet Balance
        $wallet = \App\Models\Wallet::find($tx->wallet_id);
        if ($wallet) {
            if ($tx->type === 'income') {
                $wallet->balance -= $tx->amount;
            } else {
                $wallet->balance += $tx->amount;
            }
            $wallet->save();
        }
        
        $tx->delete();
    }

    public function render()
    {
        $txQuery = auth()->user()->transactions();

        if ($this->searchTx) {
            $txQuery->where('title', 'like', '%' . $this->searchTx . '%');
        }

        if ($this->filterStartDate) {
            $txQuery->whereDate('transaction_date', '>=', $this->filterStartDate);
        }

        if ($this->filterEndDate) {
            $txQuery->whereDate('transaction_date', '<=', $this->filterEndDate);
        }

        if ($this->filterType) {
            $txQuery->where('type', $this->filterType);
        }

        if ($this->sortNominal === 'asc') {
            $txQuery->orderBy('amount', 'asc');
        } elseif ($this->sortNominal === 'desc') {
            $txQuery->orderBy('amount', 'desc');
        } else {
            $txQuery->latest('transaction_date');
        }

        $billQuery = auth()->user()->bills();
        if ($this->filterBill === 'Belum Dibayar') {
            $billQuery->where('status', '!=', 'Paid');
        } elseif ($this->filterBill === 'Sudah Dibayar') {
            $billQuery->where('status', 'Paid');
        }
        $bills = $billQuery->orderByRaw("CASE WHEN status = 'Paid' THEN 1 ELSE 0 END")->latest()->paginate(9, ['*'], 'billPage');

        return view('livewire.keuangan', [
            'wallets' => auth()->user()->wallets,
            'transactions' => $txQuery->paginate(10),
            'budgets' => auth()->user()->budgets,
            'bills' => $bills,
            'goals' => auth()->user()->goals()->latest()->get(),
        ]);
    }
}
