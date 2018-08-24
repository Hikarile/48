<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Account;
use App\Model\Album;
use App\Model\Image;

class Image extends Model
{
    protected $fillable = [
        'album_id',
        'image_id',
        'name',
        'title',
        'description',
        'width',
        'height',
        'size',
        'views',
        'delete_token',
    ];

    public function views_add(){
        $this->views = $this->views+1;
        $this->save();
    }
}
