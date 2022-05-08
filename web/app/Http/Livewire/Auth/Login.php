<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $username;
    public $password;

    protected $rules = [
        "username" => "required",
        "password" => "required",
    ];

    protected $messages = [
        "username.required" => "Username tidak boleh kosong.",
        "password.required" => "Password tidak boleh kosong.",
    ];

    public function render()
    {
        return view("auth.login");
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(["username" => $this->username, "password" => $this->password])) {
            return redirect()->to("/");
        } else {
            return redirect()->to("/login");
        }
    }
}
