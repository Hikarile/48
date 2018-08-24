<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'account_id',
        'account',
        'bio',
        'token'
    ];

    public function albums(){
        return $this->hasMany(Album::class);
    }
}
