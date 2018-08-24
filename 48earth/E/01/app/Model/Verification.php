<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'text',
        'da'
    ];
}
