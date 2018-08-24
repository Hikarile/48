<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'train_id',
        'train_name',
        'code',
        'phone',
        'day',
        'time',
        'from',
        'to',
        'money',
        'count',
    ];
}
