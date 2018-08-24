<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = [
       'train_id',
       'station_id',
       'time',
       'stop_time',
       'money',
    ];
}
