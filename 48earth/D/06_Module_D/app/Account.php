<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;
use App\Album;
use App\Image;

class Account extends Model
{
    protected $fillable = [
       'account_id',
       'account',
       'bio',
       'token',
    ];
    
    public function albums(){
        return $this->hasMany(Album::class);
    }
}
