<?php

namespace App\Traits;

use \App\Album;

/**
 *  Some methods used in apps
 */
trait Process
{
    public static function token($len)
    {
        if($len <= 0){
            return false;
        }
        
        $r = [];
    
        $arr = range('0','9');
        shuffle($arr);
        $r[] = $arr[0];
        
        $arr = range('a','z');
        shuffle($arr);
        $r[] = $arr[0];
        
        $arr = range('A','Z');
        shuffle($arr);
        $r[] = $arr[0];
        
        $arr = array_merge(range('A','Z'),range('a','z'),range('0','9'));
        shuffle($arr);
        $r = array_merge($r, array_slice($arr, 3, $len-4));
        shuffle($r);
        $r[] = 'l';
        
        return implode('', $r);
    }

    public static function xml2array($xml_string)
    {
        $data = simplexml_load_string($xml_string);
        $data = json_decode(json_encode($data), true);
        return $data;
    }

    public function valiAlbum($account, $album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        
        if ($account->albums()->where('album_id', $album_id)->doesntExist()) {
            abort(403, '拒絕存取');
        }

        return $album;
    }

    public function valiImage($album_id, $image_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $image = $album->images()->where('image_id', $image_id)->firstOrFail();
        
        return [
            $album,
            $image
        ];
    }
}
