<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use \App\Models\User;

class Register extends Component
{
    public $name;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

    public function store()
    {
        $this->validate([
            'name'      => 'required',
            'username' => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed'
        ]);

        $user = User::create([
            'name'      => $this->name,
            'email'     => $this->email,
            'username' => $this->username,
            'password'  => bcrypt($this->password)
        ]);

        if($user) {
            session()->flash('success', 'Register Berhasil!.');
            return redirect()->to('/login');
        }

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
