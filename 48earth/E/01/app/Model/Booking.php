<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'train_id',
        'booking_id',
        'cellphone',
        'booking_day',
        'start_day',
        'time',
        'station_s',
        'station_e',
        'day',
        'count',
        'money_one',
        'money_all',
        'del'
    ];
}
