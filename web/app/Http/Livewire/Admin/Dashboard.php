<?php

namespace App\Http\Livewire\Admin;

use App\Models\Summary;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'summary' => Summary::all(),
            'user' => Auth::user()
        ]);
    }
}
