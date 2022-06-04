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
    public $test;
    public $payload;

    public function mount($id)
    {
        $summary = SummaryRecaps::all();
        $summary_activity_title = [];

        foreach ($summary as $summary => $summary_item) {
            $divisi = $summary_item['divisi'];
            foreach ($divisi as $divisi => $summary_item_item) {
                $summary_activity_title[] = strtolower($summary_item_item["divisi"]);
            }
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
                    if ($user->hasRole(["sekretariat", "superadmin", "admin"])) {
                        $this->payload = SummaryRecaps::where("divisi.divisi", "Sekretariat")->get();

                        $this->test = $this->payload[0]["divisi"]['0'];

                    } else {
                        return redirect()->to("/");
                    }
                    break;
                case "penta":
                    if ($user->hasRole(["penta", "superadmin", "admin"])) {
                        $this->payload = SummaryRecaps::where("divisi.divisi", "Penta")->get();
                        $this->test = $this->payload[0]["divisi"]['1'];

                    } else {
                        return redirect()->to("/");
                    }
                    break;
                case "lattas":
                    if ($user->hasRole(["lattas", "superadmin", "admin"])) {
                        $this->payload = SummaryRecaps::where("divisi.divisi", "Lattas")->get();
                        $this->test = $this->payload[0]["divisi"]['2'];

                    } else {
                        return redirect()->to("/");
                    }
                    break;
                case "hi":
                    if ($user->hasRole(["hi", "superadmin", "admin"])) {
                        $this->payload = SummaryRecaps::where("divisi.divisi", "HI")->get();
                        $this->test = $this->payload[0]["divisi"]['3'];

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
            "test" => $this->test
        ]);
    }
}
