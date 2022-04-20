<?php

namespace App\Http\Livewire\App;

use Livewire\Component;
use App\Models\SummaryRecaps;

class HandleActivity extends Component
{
    public $titles;
    public $isActive;

    public function mount($id)
    {
        $summary = SummaryRecaps::all();
        $summary_activity_title = array();

        foreach ($summary as $summary => $summary_item) {
            $summary_activity_title[] = strtolower($summary_item['name']);
        }

        if (in_array($id, $summary_activity_title)) {
            $this->titles = $summary_activity_title;
            $this->isActive = $id;
        } else {
            return redirect()->to('/');
        }

    }
    public function render()
    {
        return view('livewire.dashboard.activity', [
            'titles' => $this->titles,
            'isActive'=> $this->isActive,

            'content_type' => false
        ]);
    }
}
