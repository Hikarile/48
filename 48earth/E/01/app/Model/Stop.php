<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'train_id',
        'station_s',
        'station_e',
        'stop_time',
        'time',
        'moeny'
    ];
}
