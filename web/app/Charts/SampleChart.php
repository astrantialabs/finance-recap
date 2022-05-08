<?php

declare(strict_types=1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use App\Models\SummaryRecaps;

class SampleChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $summary = SummaryRecaps::all()->where("name", "Sekretariat");
        $subKegiatan = [];
        $keuangan = [];
        $realisasiFisik = [];

        foreach ($summary as $summary_item) {
            $activity = $summary_item["activity"];

            foreach ($activity as $activity_item) {
                $subKegiatan[] = $activity_item["activity"];
                $keuangan[] = $activity_item["finance"];
                $realisasiFisik[] = $activity_item["physical"];
            }
        }

        return Chartisan::build()
            ->labels($subKegiatan)
            ->dataset("Keuangan", $keuangan)
            ->dataset("Realisasi Fisik", $realisasiFisik);
    }
}
