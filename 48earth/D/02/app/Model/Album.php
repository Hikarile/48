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
       'count',
       'cover',
    ];

    public function images(){
        return $this->hasMany(Image::class)->where('delete', '');
    }

    public function images_latest(){
        return $this->hasMany(Image::class)->orderBy('id', 'DESC')->limit(3);
    }

    public function images_hots(){
        return $this->hasMany(Image::class)->orderBy('view', 'DESC')->limit(3);
    }

    public function images_count(){
        $this->count =  $this->hasMany(Image::class)->where('delete', '')->count();
        $this->save();
        return ;
    }
}
