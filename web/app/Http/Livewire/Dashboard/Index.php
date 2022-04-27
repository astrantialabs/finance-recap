<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\SummaryRecaps;

class Index extends Component
{
    public function render()
    {
        $summary = SummaryRecaps::all();
        return view("dashboard.index", [
            "summary" => $summary,
        ]);
    }
}
