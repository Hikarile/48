<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class Type extends Model
{
    protected $fillable = [
        'name',
        'speed',
    ];

    public function trains(){
       return $this->hasMany(Train::class);
    }
}
