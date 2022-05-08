<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SummaryRecaps extends Model
{
    protected $collection = "summary_recaps";
    protected $connection = "mongodb";
}
