<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $showPassword = false;
    public $showRegisterModal = false;

    // Register fields
    public $registerName = '';
    public $registerEmail = '';
    public $registerPassword = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = \App\Models\User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Email tidak terdaftar.');
            return;
        }

        if (!\Illuminate\Support\Facades\Hash::check($this->password, $user->password)) {
            $this->addError('password', 'Password salah.');
            return;
        }

        \Illuminate\Support\Facades\Auth::login($user);
        session(['is_new_user' => false]);
        return redirect()->route('dashboard');
    }

    public function register()
    {
        $this->validate([
            'registerName' => 'required|min:3',
            'registerEmail' => 'required|email|unique:users,email',
            'registerPassword' => 'required|min:6'
        ]);

        $user = \App\Models\User::create([
            'name' => $this->registerName,
            'email' => $this->registerEmail,
            'password' => \Illuminate\Support\Facades\Hash::make($this->registerPassword),
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        session(['is_new_user' => true]);
        
        return redirect()->route('dashboard');
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function toggleRegisterModal()
    {
        $this->showRegisterModal = !$this->showRegisterModal;
    }

    public function render()
    {
        return view('livewire.login');
    }
}
