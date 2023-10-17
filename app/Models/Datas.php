<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Datas extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'datas';
}
