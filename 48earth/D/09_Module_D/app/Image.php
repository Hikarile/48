<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;
use App\Album;
use App\Image;

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
         $this->views +=1 ;
         $this->save();
     }
}
