<?php

namespace App\Livewire;

use Livewire\Component;

class Goals extends Component
{
    public $showAddModal = false;
    public $showManageModal = false;
    
    // Add Goal
    public $goalName;
    public $goalTarget;
    public $goalEstimate;
    public $goalMonthly;
    public $goalColor = 'emerald';

    // Manage Goal
    public $manageGoalId;
    public $depositAmount;
    public $depositWallet;

    public function saveGoal()
    {
        $this->validate([
            'goalName' => 'required|string|max:255',
            'goalTarget' => 'required|numeric',
            'goalEstimate' => 'required|date',
            'goalMonthly' => 'required|numeric',
            'goalColor' => 'required|in:emerald,blue,amber,rose,purple',
        ]);

        auth()->user()->goals()->create([
            'title' => $this->goalName,
            'target_amount' => $this->goalTarget,
            'estimate_date' => $this->goalEstimate,
            'monthly_capacity' => $this->goalMonthly,
            'color' => $this->goalColor,
            'collected_amount' => 0,
        ]);

        $this->reset(['goalName', 'goalTarget', 'goalEstimate', 'goalMonthly', 'goalColor', 'showAddModal']);
        $this->goalColor = 'emerald';
    }

    public function openManageModal($id)
    {
        $this->manageGoalId = $id;
        $this->showManageModal = true;
    }

    public function depositGoal()
    {
        $this->validate([
            'depositAmount' => 'required|numeric',
            'depositWallet' => 'nullable|exists:wallets,id',
        ]);

        $goal = auth()->user()->goals()->findOrFail($this->manageGoalId);
        
        if ($this->depositWallet) {
            $wallet = auth()->user()->wallets()->find($this->depositWallet);
            if ($wallet->balance < $this->depositAmount) {
                $this->addError('depositAmount', 'Saldo dompet tidak mencukupi');
                return;
            }
            
            $wallet->balance -= $this->depositAmount;
            $wallet->save();

            // create an expense transaction
            auth()->user()->transactions()->create([
                'title' => 'Deposit ke Goal: ' . $goal->title,
                'type' => 'expense',
                'amount' => $this->depositAmount,
                'wallet_id' => $this->depositWallet,
                'category' => 'Tabungan',
                'transaction_date' => now(),
            ]);
        }

        $goal->collected_amount += $this->depositAmount;
        $goal->save();

        $this->reset(['depositAmount', 'depositWallet', 'manageGoalId', 'showManageModal']);
    }

    public function render()
    {
        return view('livewire.goals', [
            'goals' => auth()->user()->goals,
            'wallets' => auth()->user()->wallets,
        ]);
    }
}
