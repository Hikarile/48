<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Account;
use App\Model\Album;
use App\Model\Image;

class Album extends Model
{
    protected $fillable = [
        'account_id',
        'album_id',
        'title',
        'description',
        'cover',
    ];

    public function images(){
        return $this->hasMany(Image::class)->where('delete', '');
    }
    
    public function latest(){
        return $this->hasMany(Image::class)->where('delete', '')->orderBy('id', 'DESC')->limit(3);
    }

    public function hot(){
        return $this->hasMany(Image::class)->where('delete', '')->orderBy('view', 'DESC')->limit(3);
    }
}
