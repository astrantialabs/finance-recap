<?php

namespace App\Http\Livewire\Admin;

use App\Models\Summary;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $summaries = Summary::all();

        function filter_array($array, $term) {
            $matches = array();
            foreach($array as $a){
                if($a['id'] == $term)
                    $matches[]=$a;
            }
            return $matches;
        }
        $sekretariat = filter_array($summaries, '1');
        $penta = filter_array($summaries, '2');
        $lattas = filter_array($summaries, '3');
        $hi = filter_array($summaries, '4');

        return view('livewire.admin.dashboard', [
            'summary' => Summary::all(),
            'user' => Auth::user(),
            'sekretariat' => $sekretariat,
            'penta' => $penta,
            'lattas' => $lattas,
            'hi' => $hi
        ]);
    }
}
