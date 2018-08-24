<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Syop;
use App\Book;

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
