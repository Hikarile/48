<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;

class Type extends Model
{
    protected $fillable = [
        'name',
        'speed',
    ];
}
