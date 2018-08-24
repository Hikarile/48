<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'speed'
    ];

    public function trains()
    {
        return $this->hasMany(Train::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($type) {
            $type->trains()->delete();
        });
    }
}
