<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
       'album_id',
       'image_id',
       'name',
       'title',
       'description',
       'file_name',
       'width',
       'height',
       'size',
       'view',
       'delete'
    ];

    public function view_add(){
        $this->view = $this->view+1;
        $this->save();
        return ;
    }
}
