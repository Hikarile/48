<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    public $guarded = [];

    public function images()
    {
        return $this->hasMany(Image::class)->whereNull('delete_token');
    }


}
