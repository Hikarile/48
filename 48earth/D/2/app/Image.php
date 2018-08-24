<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $guarded = [];

    public static function getImagePath($image_id)
    {
        return base_path('images/' . $image_id . '.jpg');
    }
}
