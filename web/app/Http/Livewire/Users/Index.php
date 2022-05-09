<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $users = User::all();
        return view("users.index", [
            "users" => $users,
        ]);
    }

    public $username;

    protected $rules = [
        "username" => "required",
    ];

    public function edit()
    {
        $this->validate();

        return redirect()->to("/users/edit/$this->username");
    }
}
