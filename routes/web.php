<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Keuangan;
use App\Livewire\Goals;
use App\Livewire\Statistik;
use App\Livewire\Settings;
use App\Livewire\Login;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', Login::class)->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/keuangan', Keuangan::class)->name('keuangan');
    Route::get('/goals', Goals::class)->name('goals');
    Route::get('/statistik', Statistik::class)->name('statistik');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/notifications', \App\Livewire\Notifications::class)->name('notifications');
    Route::get('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
