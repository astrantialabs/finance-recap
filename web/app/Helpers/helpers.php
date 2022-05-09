<?php

use App\Models\Utilities;
use Illuminate\Support\Facades\Storage;

if (!function_exists("divnum")) {
    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : $numerator / $denominator;
    }
}
if (!function_exists("utilities")) {
    function utilities()
    {
        $utility = Utilities::all();

        $originalDate = $utility[0]["last_runned"];
        $timestamp = strtotime($originalDate);
        $newDate = date("y-m-d-H-i-s", $timestamp);

        return $newDate;
    }
}

if (!function_exists("gridfs")) {
    function gridfs($format = "pdf")
    {
        $client = new MongoDB\Client(
            "mongodb+srv://mirae:mirae@disnakerfinancerecap.7y6vb.mongodb.net/DisnakerFinanceRecap?retryWrites=true&w=majority"
        );
        $db = $client->ProSekretariat;
        $bucket = $db->selectGridFSBucket();

        if ($format == "pdf") {
            $file = $bucket->findOne(["filename" => utilities() . ".pdf"]);
            $contents = stream_get_contents($file);
            Storage::disk("local")->put("pdf/" . utilities() . ".pdf", $contents);
        } else {
            $file = $bucket->findOne(["filename" => utilities() . ".xlsx"]);
            $contents = stream_get_contents($file);
            Storage::disk("local")->put("xlsx/" . utilities() . ".xlsx", $contents);
        }
    }
}
