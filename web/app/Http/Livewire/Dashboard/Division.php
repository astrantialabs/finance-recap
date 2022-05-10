<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\SummaryRecaps;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Division extends Component
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

        $user = User::find(auth()->user()->id);

        if (in_array($id, $summary_activity_title)) {
            switch ($id) {
                case "sekretariat":
                    if ($user->hasRole(["sekretariat", "super-admin"])) {
                        $this->payload = SummaryRecaps::all()->where("name", "Sekretariat");
                    } else {
                        return redirect()->to("/");
                    }
                    break;
                case "penta":
                    if ($user->hasRole(["penta", "super-admin"])) {
                        $this->payload = SummaryRecaps::all()->where("name", "Penta");
                    } else {
                        return redirect()->to("/");
                    }
                    break;
                case "lattas":
                    if ($user->hasRole(["lattas", "super-admin"])) {
                        $this->payload = SummaryRecaps::all()->where("name", "Lattas");
                    } else {
                        return redirect()->to("/");
                    }
                    break;
                case "hi":
                    if ($user->hasRole(["hi", "super-admin"])) {
                        $this->payload = SummaryRecaps::all()->where("name", "HI");
                    } else {
                        return redirect()->to("/");
                    }
                    break;
            }

            $this->titles = $summary_activity_title;
            $this->isActive = $id;
        } else {
            return redirect()->to("/");
        }
    }
    public function export($division, $type)
    {
        if ($type == "pdf") {
            gridfs($division, "pdf");
            redirect()->to("/storage/files/$division.pdf");
        } else {
            gridfs($division, "xlsx");
            redirect()->to("/storage/files/$division.xlsx");
        }
    }
    public function render()
    {
        return view("dashboard.division", [
            "payload" => $this->payload,
        ]);
    }
}
