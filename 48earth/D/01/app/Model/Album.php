<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'count',
        'link',
        'token',
        'created'
    ];

}
