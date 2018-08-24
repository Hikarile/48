<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Account;
use App\Model\Album;
use App\Model\Image;

class AlbumController extends Controller
{
    private function token()
    {
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 4, rand(5,11)));
    }

    public function albumAdd(Request $request)
    {
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $data['album_id'] = $this->token();
        $data['count'] = 0;
        $data['cover'] = '[]';
        $album = $request->account->albums()->create($data);
        return response()->view('album.album_add', $album, 200)->header('Content-Type', 'application/xml');
    }

    public function albumGet($album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        return response()->view('album.album_get', compact('album'), 200)->header('Content-Type', 'application/xml');
    }

    public function albumLatest($album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        return response()->view('album.album_latest', compact('album'), 200)->header('Content-Type', 'application/xml');
    }

    public function albumHot($album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        return response()->view('album.album_hot', compact('album'), 200)->header('Content-Type', 'application/xml');
    }

    public function albumPatch(Request $request, $album_id)
    {
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $album = $request->account->albums()->where('album_id', $album_id)->firstOrFail();

        if (isset($data['covers']) and isset($data['covers']['cover'])) {
            if (is_array($data['covers']['cover']) && count($data['covers']['cover']) > 3) {
                return abort(400, '');
            } else {
                $data['cover'] = json_encode($data['covers']['cover']);
            }
        } else {
            $data['cover'] = '[]';
        }
        
        $album->update($data);
        return response()->view('album.album_patch')->header('Content-Type', 'application/xml');
    }
    
    public function albumDelete(Request $request, $album_id)
    {
        $album = $request->account->albums()->where('album_id',$album_id)->firstOrFail();
        $images = $album->images;
        foreach($images as $image){
            unlink(base_path('images\\'.$image->name));
            $image->delete();
        }
        $album->delete();
        return response()->view('album.album_delete')->header('Content-Type', 'application/xml');
    }

    public function cover($album_id){
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $covers = json_decode($album->cover);
        
        $images = [];
        foreach ($covers as $key => $cover) {
            $image = Image::where('image_id', $cover)->first();
            if($image != ''){
                $tf = true;
                foreach($images as $val){
                    if($val['name'] ==  $image->name){
                        $tf = false;
                    }
                }
                if($tf){
                    $images[] = [
                        'width' => $image->width,
                        'height' => $image->height,
                        'name' => base_path('images\\'.$image->name)
                    ];
                }
            }
        }
        
        $new_image = imagecreatetruecolor(90, 90);
        switch(count($images)){
            case"1":
                $image = imagecreatefromjpeg($images[0]['name']);
                if($images[0]['width'] > $images[0]['height']){
                    $w = $images[0]['height'];
                    $h = $images[0]['height'];
                }else{
                    $w = $images[0]['width'];
                    $h = $images[0]['width'];
                }
                imagecopyresized($new_image, $image, 0, 0, ($images[0]['width']/2)-($w/2), ($images[0]['height']/2)-($h/2), 90, 90, $w, $h);
            break;
            case"2":
                for($i = 0; $i <= 1; $i ++){
                    $image = imagecreatefromjpeg($images[$i]['name']);
                    if ($images[$i]['height'] > $images[$i]['width'] * 2) {
                        $h = $images[$i]['width'] * 2;
                        $w = $images[$i]['width'];
                    } else {
                        $h = $images[$i]['height'];
                        $w = $images[$i]['height'] / 2;
                    }
                    imagecopyresized($new_image, $image, (45*$i), 0, ($images[$i]['width']/2)-($w/2), ($images[$i]['height']/2)-($h/2), 45, 90, $w, $h);
                }    
            break;
            case"3":
                $image = imagecreatefromjpeg($images[0]['name']);
                if($images[0]['width'] > $images[0]['height']){
                    $w = $images[0]['height'];
                    $h = $images[0]['height'];
                }else{
                    $w = $images[0]['width'];
                    $h = $images[0]['width'];
                }
                imagecopyresized($new_image, $image, 0, 0, ($images[0]['width']/2)-($w/2), ($images[0]['height']/2)-($h/2), 90, 90, $w, $h);

                for($i = 1; $i <= 2; $i ++){
                    $image = imagecreatefromjpeg($images[$i]['name']);
                    if($images[$i]['width'] > $images[$i]['height']){
                        $w = $images[$i]['height'];
                        $h = $images[$i]['height'];
                    }else{
                        $w = $images[$i]['width'];
                        $h = $images[$i]['width'];
                    }
                    imagecopyresized($new_image, $image, 45*($i-1), 45, ($images[$i]['width']/2)-($w/2), ($images[$i]['height']/2)-($h/2), 45, 90, $w, $h);
                }    
            break;
        }

        ob_start();
        imagejpeg($new_image);
        $content = ob_get_contents();
        ob_end_clean();
        return response($content)->header('Content-type', 'image/jpeg');
    }
    
}
