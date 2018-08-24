<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Album;
use App\Image;

class ImageController extends Controller
{
    public function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 1, 10));
    }
    public function delete_token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 1, 16));
    }
    public function add(Request $request, $album_id){
        $name = $this->change($request->image);
        $url =base_path('images//'.$name);

        $data['image_id'] = $this->token();
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['name'] = $name;
        $data['width'] = getimagesize($url)[0];
        $data['height'] = getimagesize($url)[1];
        $data['size'] = filesize($url);
        $data['views'] = 0;
        $data['delete_token'] = "";

        $album = $request->account->albums()->where('album_id', $album_id)->firstOrFail();
        $image = $album->images()->create($data);
        return response()->view('image.add', compact('image'), 200)->header('Content-Type', 'application/xml');
    }
    public function get(Request $request, $album_id, $image_id){
        $image = Album::where('album_id', $album_id)->first()->images()->where('image_id', $image_id)->first();
        return response()->view('image.get', compact('image'), 200)->header('Content-Type', 'application/xml');
    }
    public function patch(Request $request, $album_id, $image_id){
        $data = $this->exp($request->getContent());
        if(isset($data['name'])){
            $image = Image::where('image_id', $image_id)->firstOrFail();
            unlink(base_path('images\\'.$image->name));

            $name = $this->change($data['name']);
            $url =base_path('images//'.$name);

            $data['name'] = $name;
            $data['width'] = getimagesize($url)[0];
            $data['height'] = getimagesize($url)[1];
            $data['size'] = filesize($url);
        }

        $image =  $request->account->albums()->where('album_id', $album_id)->first()->images()->where('image_id', $image_id)->first();
        $image->update($data);
        return response()->view('image.patch', compact('image'), 200)->header('Content-Type', 'application/xml');
    }
    public function delete(Request $request, $album_id, $image_id){
        $image =  $request->account->albums()->where('album_id', $album_id)->first()->images()->where('image_id', $image_id)->firstOrFail();
        $image->delete_token = $this->delete_token();
        $image->save();
        return response()->view('image.delete', compact('image'), 200)->header('Content-Type', 'application/xml');
    }
    public function undelete(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $album =  Album::where('album_id', $data['dst_album'])->first();
        $image =  Image::where('delete_token', $data['delete_token'])->where('album_id', $album->id)->firstOrFail();
        $image->delete_token = "";
        $image->save();
        return response()->view('move', compact('image'), 200)->header('Content-Type', 'application/xml');
    }
    
    public function get_img($image_id, $size){
        if(strlen($image_id) != 10){
            $image_id .= $size;
            $size = "";
        }
        $image = Image::where('delete_token', '')->where('image_id', $image_id)->firstOrFail();
        $image->views_add();
        $or_image = imagecreatefromjpeg('images\\'.$image->name);
        $or_width = $image->width;
        $or_height = $image->height;

        switch($size){
            case"":
                $w = $or_width;
                $h = $or_height;
            break;
            case"l":
                $w = min(960, $or_width);
                $h = min(960, $or_height);
            break;
            case"m":
                $w = min(320, $or_width);
                $h = min(320, $or_height);
            break;
            case"s":
                $w = min(90, $or_width);
                $h = min(60, $or_height);
            break;
            case"t":
                $w = 50;
                $h = 50;
            break;
            default:
                return abort(400);
        }
        if($size != "t"){
            if($or_width > $or_height){
                $h = $or_height * ($w / $or_width);
            }else{
                $w = $or_width * ($h / $or_height);
            }
        }
        $new_image = imagecreatetruecolor($w, $h);
        imagecopyresized($new_image, $or_image, 0, 0, 0, 0, $w, $h, $or_width, $or_height);
        
        ob_start();
        imagejpeg($new_image);
        $content = ob_get_contents();
        ob_end_clean();
        return response($content)->header('Content-Type', 'image/jpeg');
    }

    public function exp($data){
        $array = [];
        $title = explode("\r\n", $data)[0];
        foreach( array_slice(explode($title, $data), 1, -1) as $val){
            $key = trim(explode('=', explode(';', explode("\r\n\r\n", $val)[0])[1])[1], '"');
            $value = trim(explode("\r\n\r\n", $val)[1], "\r\n");
            if($key == "image"){
                $key = "name";
            }
            $array[$key] = $value;
        }
        return $array;
    }
    public function change($data){  
        try{
            if(gettype($data) == "string"){
                $data = imagecreatefromstring($data);
            }else{
                switch($data->getClientmimeType()){
                    case"image/jpeg":
                        $data = imagecreatefromjpeg($data);
                    break;
                    case"image/png":
                        $data = imagecreatefrompng($data);
                    break;
                    case"image/gif":
                        $data = imagecreatefromgif($data);
                    break;
                    default:
                        throw new \Exception;
                }
            }
        } catch(\Exception $e){
            return abort(400);
        }

        $name = uniqid(rand()).'.jpg';
        $url =base_path('images//'.$name);
        imagejpeg($data, $url, 20);
        return $name;
    }
}
