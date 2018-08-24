<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Account;
use App\Model\Album;
use App\Model\Image;

class AlbumController extends Controller
{
    public function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 4, rand(5, 11)));
    }

    public function add(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $data['album_id'] = $this->token();
        $data['cover'] = "[]";
        $album = $request->account->albums()->create($data);
        return response()->view('album.add', compact('album'), 200)->header('Content-Type', 'application/xml');
    }

    public function get(Request $request, $album_id){
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $account = Account::where('id', $album->id)->firstOrFail();
        return response()->view('album.get', compact('account', 'album'), 200)->header('Content-Type', 'application/xml');
    }

    public function latest(Request $request, $album_id){
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $account = Account::where('id', $album->id)->firstOrFail();
        return response()->view('album.latest', compact('account', 'album'), 200)->header('Content-Type', 'application/xml');
    }

    public function hot(Request $request, $album_id){
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $account = Account::where('id', $album->id)->firstOrFail();
        return response()->view('album.hot', compact('account', 'album'), 200)->header('Content-Type', 'application/xml');
    }

    public function delete(Request $request, $album_id){
        $album = $request->account->albums()->where('album_id', $album_id)->firstOrFail();
        $images = Image::where('album_id', $album->id)->get();
        foreach($images as $image){
            unlink(base_path('images\\'.$image->name));
            $image->delete();
        }
        $album->delete();
        return response()->view('album.delete')->header('Content-Type', 'application/xml');
    }

    public function patch(Request $request, $album_id){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $d['title'] = $data['title'];
        $d['description'] = $data['description'];
        if(isset($data['covers']) && isset($data['covers']['cover'])){
            if(is_array($data['covers']['cover'])){
                if(count($data['covers']['cover']) <= 3){
                    $d['cover'] = json_encode($data['covers']['cover']);
                }else{
                    return abort(400);
                }
            }else{
                $d['cover'] = json_encode($data['covers']['cover']);
            }
        }else{
            $d['cover'] = "[]";
        }
        $album = $request->account->albums()->where('album_id', $album_id)->firstOrFail()->update($d);
        return response()->view('album.patch')->header('Content-Type', 'application/xml');
    }

    public function move(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $image = Image::where('image_id', $data['src_image'])->firstOrFail();
        $album = $request->account->albums()->where('album_id', $data['dst_album'])->firstOrFail();
        $image->album_id = $album->id;
        $image->save();
        return response()->view('move')->header('Content-Type', 'application/xml');
    }

    public function cover(Request $request, $album_id){
        $album = $request->account->albums()->where('album_id', $album_id)->firstOrFail();
        $covers = json_decode($album->cover, true);
        $data = [];
        foreach($covers as $key => $cover){
            $image = Image::where('image_id', $cover)->first();
            if($image != ""){
                $tf = true;
                if($key != 0){
                    foreach($data as $val){
                        if($val['name'] == base_path('images\\'.$image->name)){
                            $tf = false;
                        }
                    }
                }
                if($tf){
                    $data[] = [
                        'name' => base_path('images\\'.$image->name),
                        'width' => $image->width,
                        'height' => $image->height
                    ];
                }
            }
        }

        $new_image = imagecreatetruecolor(90, 90);
        switch(count($data)){
            case"1":
                $image = imagecreatefromjpeg($data[0]['name']);
                if($data[0]['width'] > $data[0]['height']){
                    $w =  $data[0]['height'];
                    $h =  $data[0]['height'];
                }else{
                    $w =  $data[0]['width'];
                    $h =  $data[0]['width'];
                }
                imagecopyresized($new_image, $image, 0, 0, ($data[0]['width']/2) - ($w/2), ($data[0]['height']/2) - ($h/2), 90, 90 , $w, $h);
            break;
            case"2":
                for($i = 0;$i <= 1 ; $i++){
                    $image = imagecreatefromjpeg($data[$i]['name']);
                    if($data[$i]['width']*2 < $data[$i]['height']){
                        $w =  $data[$i]['width'];
                        $h =  $data[$i]['width'] * 2;
                    }else{
                        $w =  $data[$i]['height'] / 2;
                        $h =  $data[$i]['height'];
                    }
                    imagecopyresized($new_image, $image, 45*$i, 0, ($data[$i]['width']/2) - ($w/2), ($data[$i]['height']/2) - ($h/2), 45, 90 , $w, $h);
                }
            break;
            case"3":
            $image = imagecreatefromjpeg($data[0]['name']);
            if($data[0]['width'] > $data[0]['height']){
                $w =  $data[0]['height'];
                $h =  $data[0]['height'];
            }else{
                $w =  $data[0]['width'];
                $h =  $data[0]['width'];
            }
            imagecopyresized($new_image, $image, 0, 0, ($data[0]['width']/2) - ($w/2), ($data[0]['height']/2) - ($h/2), 90, 90 , $w, $h);
            for($i = 1;$i <= 2 ; $i++){
                $image = imagecreatefromjpeg($data[$i]['name']);
                if($data[$i]['width']*2 < $data[$i]['height']){
                    $w =  $data[$i]['width'];
                    $h =  $data[$i]['width'] * 2;
                }else{
                    $w =  $data[$i]['height'] / 2;
                    $h =  $data[$i]['height'];
                }
                imagecopyresized($new_image, $image, 45*($i-1), 45, ($data[$i]['width']/2) - ($w/2), ($data[$i]['height']/2) - ($h/2), 45, 45 , $w, $h);
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
