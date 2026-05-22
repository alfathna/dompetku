<?php

namespace App\Livewire;

use Livewire\Component;

class Settings extends Component
{
    public $name = '';
    public $email = '';
    public $job = '';
    public $timezone = '';
    
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->job = 'Senior UI/UX Designer & Freelancer'; // keep dummy for non-db fields
        $this->timezone = $user->timezone ?? '';
    }

    public function saveProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'timezone' => 'nullable|string'
        ]);

        $user = auth()->user();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->timezone = $this->timezone;
        $user->save();

        session()->flash('profile_success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah.');
            return;
        }

        $user->password = \Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password_success', 'Password berhasil diubah!');
    }

    public function deleteAccount()
    {
        $user = auth()->user();

        // Delete all related data
        $user->transactions()->delete();
        $user->wallets()->delete();
        $user->budgets()->delete();
        $user->bills()->delete();
        $user->goals()->delete();

        // Logout and delete user
        auth()->logout();
        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
