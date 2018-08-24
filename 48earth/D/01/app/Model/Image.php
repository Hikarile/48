<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable=[
        'album_id',
        'description',
        'title',
        'image',
        'width',
        'height',
        'size',
        'views',
        'cover',
        'link',
        'tokne',
        'created',
        'delete'
    ];

}
