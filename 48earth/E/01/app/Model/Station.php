<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'chinese',
        'english'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($type) {
            if ($type->trains->first()) {
                throw new \Exception;
            }
        });
    }
}
