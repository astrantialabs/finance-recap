<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function render()
    {
        return view("auth.logout");
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->to("/login");
    }
}
