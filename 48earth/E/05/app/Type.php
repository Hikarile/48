<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Syop;
use App\Book;

class Type extends Model
{
    protected $fillable = [
        'name',
        'speed',
    ];

}
