<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\SummaryRecaps;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    public function export()
    {
        gridfs("ProSekretariat", "pdf");
        Storage::download("files/ProSekretariat.pdf");
        // if ($type == "pdf") {

        //     $file = "files/$division.pdf";
        //     Storage::download($file);
        //     // sleep(1);
        //     // return Storage::delete($file);
        // } else {
        //     gridfs("ProSekretariat", "xlsx");

        //     $file = "files/$division.xlsx";
        //     Storage::download($division);
        //     // sleep(1);
        //     // return Storage::delete($file);
        // }

        // return Storage::disk('exports')->download($division);
    }
    public function render()
    {
        gridfs("ProSekretariat", "pdf");

        $summary = SummaryRecaps::all();
        return view("dashboard.index", [
            "summary" => $summary,
        ]);
    }
}
