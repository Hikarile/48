<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;
class Station extends Model
{
    protected $fillable = [
        'chinese',
        'english',
     ];
}
