<?php

namespace App\Http\Livewire\App;

use Livewire\Component;
use App\Models\SummaryRecaps;

class Index extends Component
{
    public function render()
    {
        $summary = SummaryRecaps::all();
        $summary_activity_title = array();

        foreach ($summary as $summary => $summary_item) {
            $summary_activity_title[] = $summary_item['name'];
        }

        return view('livewire.app.index', [
            'summary' => $summary,
            'titles' => $summary_activity_title,
            'isActive'=> 'dashboard',

            'content_type' => true
        ]);
    }
}
