<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type_id',
        'train_name',
        'car_count',
        'car_people',
        'car_all',
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
        'station_s',
        'station_s_time',
        'station_e'
    ];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
}
