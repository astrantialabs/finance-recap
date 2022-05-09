<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Utilities extends Model
{
    protected $collection = "utilities";
    protected $connection = "mongodb";
}
