<?php

namespace App\Http\Livewire\App;

use Livewire\Component;
use App\Models\SummaryRecaps;

class HandleDivision extends Component
{
    public $titles;
    public $isActive;
    public $payload;

    public function mount($id)
    {
        $summary = SummaryRecaps::all();
        $summary_activity_title = [];

        foreach ($summary as $summary => $summary_item) {
            $summary_activity_title[] = strtolower($summary_item["name"]);
        }

        function filter_array($array, $term)
        {
            $matches = [];
            foreach ($array as $a) {
                if ($a["id"] == $term) {
                    $matches[] = $a;
                }
            }
            return $matches;
        }

        if (in_array($id, $summary_activity_title)) {
            switch ($id) {
                case "sekretariat":
                    $this->payload = SummaryRecaps::all()->where("name", "Sekretariat");
                    break;
                case "penta":
                    $this->payload = SummaryRecaps::all()->where("name", "Penta");
                    break;
                case "lattas":
                    $this->payload = SummaryRecaps::all()->where("name", "Lattas");
                    break;
                case "hi":
                    $this->payload = SummaryRecaps::all()->where("name", "HI");
                    break;
            }

            $this->titles = $summary_activity_title;
            $this->isActive = $id;
        } else {
            return redirect()->to("/");
        }
    }
    public function render()
    {
        return view("livewire.dashboard.division", [
            "titles" => $this->titles,
            "isActive" => $this->isActive,
            "payload" => $this->payload,

            "content_type" => false,
        ]);
    }
}
