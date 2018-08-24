<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'account_id',
        'album_id',
        'title',
        'description',
        'cover'
    ];

    public function images(){
        return $this->hasMany(Image::class)->where('delete', '');
    }

    public function images_latest(){
        return $this->hasMany(Image::class)->where('delete', '')->orderBy('id', 'DESC')->limit(3);
    }
    
    public function images_hot(){
        return $this->hasMany(Image::class)->where('delete', '')->orderBy('views', 'DESC')->limit(3);
    }

    public function getCovers(){
        return $this->images->whereIn('image_id', json_decode($this->covers, true) ?: []);
    }

}
