<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Summary extends Model
{
    protected $collection = 'summary_recaps';
    protected $connection = 'mongodb';
}
