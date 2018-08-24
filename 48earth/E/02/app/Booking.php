<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class Booking extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'train_id',
        'token',
        'phone',
        'day',
        'start_time',
        'train_name',
        'start',
        'end',
        'money',
        'count',
    ];
}
