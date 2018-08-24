<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;
class Book extends Model
{
    use softDeletes;

    protected $fillable =[
        'train_id',
        'train_name',
        'code',
        'phone',
        'day',
        'time',
        'from',
        'to',
        'count',
        'money',
    ];
}
