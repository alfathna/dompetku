<?php

namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $filter = 'semua';

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

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
        $query = auth()->user()->notifications();

        if ($this->filter === 'belum_dibaca') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'dibaca') {
            $query->whereNotNull('read_at');
        }

        return view('livewire.notifications', [
            'notifications' => $query->latest()->get(),
            'countAll' => auth()->user()->notifications()->count(),
            'countUnread' => auth()->user()->unreadNotifications()->count(),
            'countRead' => auth()->user()->notifications()->whereNotNull('read_at')->count(),
        ])->layout('layouts.app', ['title' => 'Notifikasi - Dompetku']);
    }
}
