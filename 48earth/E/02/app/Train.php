<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class Train extends Model
{
    protected $fillable = [
       'type_id',
       'name',
       'day',
       'start_time',
       'number',
       'people',
    ];

    public function stops(){
        return $this->hasMany(Stop::class);
    }
    public function bookings(){
        return $this->hasMany(booking::class);
    }
    
    public function day($date){
        return in_array(date('N', strtotime($date)), explode(',', $this->day));
    }

    public function getstop($station){
        $stop = $this->stops()->where('station_id', $station)->first();
        if ($stop != '') {
            return $stop;
        }else{
            return false;
        }
    }

}
