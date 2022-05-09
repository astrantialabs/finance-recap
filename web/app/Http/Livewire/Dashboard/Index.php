<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\SummaryRecaps;
use App\Models\Utilities;

class Index extends Component
{
    public function render()
    {
        // dd(gridfs());
        // dd(date("Y-m-d-h-m-s"));
        dd(utilities() . ".pdf");

        $summary = SummaryRecaps::all();
        return view("dashboard.index", [
            "summary" => $summary,
        ]);
    }
}
