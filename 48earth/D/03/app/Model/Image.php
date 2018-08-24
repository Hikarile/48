<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'album_id',
        'image_id',
        'title',
        'description',
        'name',
        'width',
        'height',
        'size',
        'views',
        'delete',
    ];
}
