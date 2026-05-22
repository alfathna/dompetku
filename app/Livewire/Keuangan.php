<?php

namespace App\Livewire;

use Livewire\Component;

class Keuangan extends Component
{
    public $activeTab = 'transaksi';
    
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

    // Bills
    public $showAddBillModal = false;
    public $billName;
    public $billAmount;
    public $billDate;

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

    public function saveBill()
    {
        $this->validate([
            'billName' => 'required|string|max:255',
            'billAmount' => 'required|numeric',
            'billDate' => 'required|date',
        ]);

        auth()->user()->bills()->create([
            'title' => $this->billName,
            'amount' => $this->billAmount,
            'due_date' => $this->billDate,
        ]);

        $this->reset(['billName', 'billAmount', 'billDate', 'showAddBillModal']);
    }

    public function payBill($billId)
    {
        $bill = auth()->user()->bills()->findOrFail($billId);
        $bill->update(['status' => 'Paid']);
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
        return view('livewire.keuangan', [
            'wallets' => auth()->user()->wallets,
            'transactions' => auth()->user()->transactions()->latest('transaction_date')->get(),
            'budgets' => auth()->user()->budgets,
            'bills' => auth()->user()->bills()->orderBy('due_date')->get(),
        ]);
    }
}
