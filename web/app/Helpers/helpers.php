<?php

use App\Models\Utilities;
use Dotenv\Util\Regex;
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
    function gridfs($division, $format = "pdf")
    {
        $file_name = $division;
        switch ($division) {
            case 'Sekretariat':
                $file_name = "DevSekretariat";
                break;
            case 'Lattas':
                $file_name = "DevLattas";
                break;
            case 'Penta':
                $file_name = "DevPenta";
                break;
            case 'HI':
                $file_name = "DevHI";
                break;
        }

        $client = new MongoDB\Client(
            "mongodb+srv://mirae:mirae@disnakerfinancerecap.7y6vb.mongodb.net/DisnakerFinanceRecap?retryWrites=true&w=majority"
        );
        $db = $client->$file_name;
        $bucket = $db->selectGridFSBucket();

        $file = $bucket->find([], ["sort" => ["uploadDate" => -1]]);
        $files = $file->toArray();

        $filename = [];
        foreach ($files as $file) {
            $filename = json_decode(json_encode($files), true);
        }
        $sort = [];
        foreach ($filename as $key => $value) {
            $sort[$key] = $value["filename"];
        }
        $sliced_array = array_slice($sort, 0, 2);

        if ($format == "pdf") {
            $stream = $bucket->openDownloadStreamByName($sliced_array[0]);
            $contents = stream_get_contents($stream);
            Storage::disk("local")->put("files/$division.pdf", $contents);
        } else {
            $stream = $bucket->openDownloadStreamByName($sliced_array[1]);
            $contents = stream_get_contents($stream);
            Storage::disk("local")->put("files/$division.xlsx", $contents);
        }
    }
}
