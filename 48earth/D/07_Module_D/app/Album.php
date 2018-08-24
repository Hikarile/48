<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;
use App\Album;
use App\Image;

class Album extends Model
{
    protected $fillable = [
       'account_id',
       'album_id',
       'title',
       'description',
       'covers',
    ];

    public function images(){
        return $this->hasMany(Image::class)->where('delete_token', '');
    }
    public function latest(){
        return $this->hasMany(Image::class)->where('delete_token', '')->orderBy('id', 'DESC')->limit(3);
    }
    public function hot(){
        return $this->hasMany(Image::class)->where('delete_token', '')->orderBy('views', 'DESC')->limit(3);
    }
}
