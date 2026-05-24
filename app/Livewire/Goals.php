<?php

namespace App\Livewire;

use Livewire\Component;

class Goals extends Component
{
    public $showAddModal = false;
    public $showManageModal = false;
    public $showDeleteConfirmModal = false;
    public $deleteId;
    
    // Add/Edit Goal
    public $goalId;
    public $goalName;
    public $goalTargetAmount;
    public $goalEstimateDate;
    public $goalMonthlyCapacity;

    // Manage Goal
    public $manageGoalId;
    public $depositAmount;
    public $depositWallet;
    public $depositDate;
    public $depositNotes;

    public function saveGoal()
    {
        $this->validate([
            'goalName' => 'required|string|max:255',
            'goalTargetAmount' => 'required|numeric',
            'goalEstimateDate' => 'required|date',
            'goalMonthlyCapacity' => 'required|numeric',
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
                'collected_amount' => 0,
            ]);
        }

        $this->reset(['goalId', 'goalName', 'goalTargetAmount', 'goalEstimateDate', 'goalMonthlyCapacity', 'showAddModal']);
    }

    public function editGoal($id)
    {
        $goal = auth()->user()->goals()->findOrFail($id);
        $this->goalId = $goal->id;
        $this->goalName = $goal->title;
        $this->goalTargetAmount = $goal->target_amount;
        $this->goalEstimateDate = $goal->estimate_date;
        $this->goalMonthlyCapacity = $goal->monthly_capacity;
        $this->showAddModal = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function deleteGoal()
    {
        if ($this->deleteId) {
            auth()->user()->goals()->findOrFail($this->deleteId)->delete();
        }
        $this->showDeleteConfirmModal = false;
    }

    public function openManageModal($id)
    {
        $this->manageGoalId = $id;
        $goal = auth()->user()->goals()->findOrFail($id);
        $this->goalName = $goal->title; // For display
        $this->depositAmount = null;
        $this->depositWallet = '';
        $this->depositDate = now()->format('Y-m-d');
        $this->depositNotes = '';
        $this->showManageModal = true;
    }

    public function depositGoal()
    {
        $this->validate([
            'depositAmount' => 'required|numeric|min:1',
            'depositWallet' => 'required|exists:wallets,id',
            'depositDate' => 'required|date',
        ]);

        $goal = auth()->user()->goals()->findOrFail($this->manageGoalId);
        
        $wallet = auth()->user()->wallets()->findOrFail($this->depositWallet);
        if ($wallet->balance < $this->depositAmount) {
            $this->addError('depositAmount', 'Saldo dompet tidak mencukupi');
            return;
        }
        
        $wallet->balance -= $this->depositAmount;
        $wallet->save();

        // create an expense transaction
        auth()->user()->transactions()->create([
            'title' => 'Setoran Tabungan: ' . $goal->title,
            'type' => 'saving',
            'amount' => $this->depositAmount,
            'wallet_id' => $this->depositWallet,
            'category' => 'Tabungan',
            'transaction_date' => $this->depositDate,
            'notes' => $this->depositNotes,
        ]);

        $goal->collected_amount += $this->depositAmount;
        $goal->save();

        $this->reset(['depositAmount', 'depositWallet', 'depositDate', 'depositNotes', 'manageGoalId', 'showManageModal', 'goalName']);
    }

    public function render()
    {
        return view('livewire.goals', [
            'goals' => auth()->user()->goals,
            'wallets' => auth()->user()->wallets,
        ]);
    }
}
