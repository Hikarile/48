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
        'view',
        'delete',
    ];

    public function view_add(){
        $this->view = $this->view+1;
        $this->save();
    }
}
