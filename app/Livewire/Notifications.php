<?php

namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        $this->dispatch('notifications-updated');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('notifications-updated');
    }

    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->delete();
        }
        $this->dispatch('notifications-updated');
    }

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => auth()->user()->notifications()->latest()->get(),
        ])->layout('layouts.app', ['title' => 'Notifikasi - Dompetku']);
    }
}
