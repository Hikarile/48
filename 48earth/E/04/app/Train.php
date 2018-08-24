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
       'code',
       'day',
       'start_time',
       'car',
       'people',
    ];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
    public function stops(){
        return $this->hasMany(Stop::class);
    }
    public function day($day){
        $days = ['', '一', '二', '三', '四','五', '六', '日'];
        return in_array($days[date('N', strtotime($day.date("H:i:s")))], explode(',', $this->day));
    }
    public function getstop($id){
        $stop = $this->stops()->where('station_id', $id)->first();
        if ($stop != '') {
            return $stop;
        }else{
            return false;
        }
    }
}
