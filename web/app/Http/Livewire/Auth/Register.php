<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;

class Register extends Component
{
    public $name;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        "name" => "required",
        "username" => "required",
        "email" => "required|email|unique:users",
        "password" => "required|confirmed",
    ];

    protected $messages = [
        "name.required" => "Nama tidak boleh kosong.",
        "username.required" => "username tidak boleh kosong.",
        "email.required" => "Alamat email tidak boleh kosong.",
        "password.required" => "Password tidak boleh kosong.",
    ];

    public function render()
    {
        return view("auth.register");
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "password" => bcrypt($this->password),
        ]);

        // give super-admin role if the username is liz
        if ($user->username == "liz") {
            $user->assignRole("super-admin");
        }

        if ($user) {
            return redirect()->to("/login");
        }
    }
}
