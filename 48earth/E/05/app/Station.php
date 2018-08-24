<?php

namespace App;
use App\Type;
use App\Station;
use App\Train;
use App\Syop;
use App\Book;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'chinese',
        'english',
    ];
}
