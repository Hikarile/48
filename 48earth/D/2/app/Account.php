<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $guarded = [];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }
}
