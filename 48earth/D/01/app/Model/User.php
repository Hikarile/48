<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	public $timestamps = false;
	
    protected $fillable = [
        'account',
        'bio',
		'token',
		'created'
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->attributes['password'] = Hash::make($user->attributes['password']);
        });
    }
}
 