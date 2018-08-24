<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;
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

    public function stops(){
        return $this->hasMany(Stop::class);
    }
    public function books(){
        return $this->hasMany(Book::class);
    }
    public function day($day){
        $days =['', '一', '二', '三', '四', '五', '六', '日'];
        return in_array($days[date('N', strtotime($day.date('H:i:s')))], explode(',', $this->day));
    }
    public function getstop($id){
        $station = $this->stops()->where('station_id', $id)->first();
        if($station != ''){
            return $station;
        }else{
            return '';
        }
    }
}
