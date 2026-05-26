<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    public $photo;
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
        $this->job = ''; // default empty
        $tz = $user->timezone ?? '';
        
        $map = [
            'WIB' => 'Asia/Jakarta',
            'WITA' => 'Asia/Makassar',
            'WIT' => 'Asia/Jayapura',
            'GMT+07:00 (Jakarta)' => 'Asia/Jakarta',
            'GMT+08:00 (Makassar)' => 'Asia/Makassar',
            'GMT+09:00 (Jayapura)' => 'Asia/Jayapura',
            'GMT+00:00 (London)' => 'UTC',
        ];
        
        if (isset($map[$tz])) {
            $tz = $map[$tz];
        }
        
        $this->timezone = $tz;
    }

    public function saveProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'timezone' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus berupa jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $user = auth()->user();
        
        if ($this->photo) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $this->photo->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->timezone = $this->timezone;
        $user->save();

        session()->flash('profile_success', 'Profil berhasil diperbarui!');
    }

    public function deletePhoto()
    {
        $user = auth()->user();
        if ($user->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
            $this->photo = null;
        }

        session()->flash('profile_success', 'Foto profil berhasil dihapus!');
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
