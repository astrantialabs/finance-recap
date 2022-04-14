<?php

namespace App\Http\Livewire\Auth;

use App\Models\Summary;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;

class Login extends Component
{
    public $username;
    public $password;

    public function login()
    {
        $this->validate([
            'username'     => 'required',
            'password'  => 'required'
        ]);
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        if(Auth::attempt(['username' => $this->username, 'password' => $this->password])) {

            return redirect()->to('/admin/dashboard');

        } else {
            $out->writeln("tdk sukses");

            session()->flash('error', 'Username atau Password Anda salah!.');
            return redirect()->to('/login');
        }

    }

    public function render()
    {

        return view('livewire.auth.login');
    }
}
