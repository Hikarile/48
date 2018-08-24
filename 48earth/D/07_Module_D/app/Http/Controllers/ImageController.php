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
        $url = base_path('images\\'.$name);
        
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['image_id'] = $this->token();
        $data['name'] = $name;
        $data['width'] = getimagesize($url)[0];
        $data['height'] = getimagesize($url)[1];
        $data['size'] =filesize($url);
        $data['views'] = 0;
        $data['delete_token'] = "";

        $image =$request->account->albums()->where('album_id', $album_id)->firstOrFail()->images()->create($data);
        return response()->view('image.add', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function get(Request $request, $album_id, $image_id){
        $image = Album::where('album_id', $album_id)->firstorFail()->images()->where('image_id', $image_id)->firstorFail();
        return response()->view('image.get', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function patch(Request $request, $album_id, $image_id){
        $image = $request->account->albums()->where('album_id', $album_id)->firstorFail()->images()->where('image_id', $image_id)->firstorFail();
        $data = $this->exp($request->getContent());
        
        if(isset($data['name']) && $data['name'] != ''){
            $name = $this->change($data['name']);
            unlink(base_path('images\\'.$image->name));

            $url = base_path('images\\'.$name);

            $data['name'] = $name;
            $data['width'] = getimagesize($url)[0];
            $data['height'] = getimagesize($url)[1];
            $data['size'] =filesize($url);
        }
        $image->update($data);
        return response()->view('image.patch', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function delete(Request $request, $album_id, $image_id){
        $image = $request->account->albums()->where('album_id', $album_id)->firstOrFail()->images()->where('image_id', $image_id)->firstOrFail();
        $image->delete_token = $this->delete_token();
        $image->save();
        return response()->view('image.delete', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function undelete(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $album = Album::where('album_id', $data['dst_album'])->firstOrFail();
        $image = Image::where('delete_token', $data['delete_token'])->where('album_id', $album->id)->firstOrFail();
        $image->delete_token = "";
        $image->save();
        return response()->view('move', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function image(Request $request, $image_id, $size){
        if(strlen($image_id) != 10){
            $image_id .= $size;
            $size ='';
        }
        $image = Image::where('image_id', $image_id)->firstOrFail();

        $or_image = imagecreatefromjpeg(base_path('images\\'.$image->name));
        $or_width = $image->width;
        $or_height= $image->height;
        
        switch($size){
            case"":
            $new_width = $or_width;
            $new_height= $or_height;
            break;
            case"l":
            $new_width = min(960, $or_width);
            $new_height= min(960, $or_height);
            break;
            case"m":
            $new_width = min(320, $or_width);
            $new_height= min(320, $or_height);
            break;
            case"s":
            $new_width = min(90, $or_width);
            $new_height= min(60, $or_height);
            break;
            case"t":
            $new_width = 50;
            $new_height= 50;
            break;
        }

        if($size != "t"){
            if($or_width < $or_height){
                $new_width = $or_width * ($new_height / $or_height);
            }else{
                $new_height = $or_height * ($new_width / $or_width);
            }
        }

        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresized($new_image, $or_image, 0, 0, 0, 0, $new_width, $new_height, $or_width, $or_height);

        ob_start();
        imagejpeg($new_image);
        $content = ob_get_contents();
        ob_end_clean();
        return response($content)->header('Content-Type', 'image/jpeg');
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
                    throw new Exception;
                }
            }
        } catch( \Exception $e){
            return abort(400);
        }
        
        $name = uniqid(rand()).'.jpg';
        $url = base_path('images\\'.$name);
        imagejpeg($data, $url, 20);
        return $name;
    }
    public function exp($img){
        $title = explode("\r\n", $img)[0];
        $data = [];
        foreach(array_slice(explode($title, $img), 1, -1) as $val){
            $val = explode("\r\n\r\n", $val);
            $key = trim(explode('=', explode('; ', $val[0])[1])[1], '"');
            $value = trim($val[1], "\r\n");
            if($key == "image"){
                $data["name"] = $value;
            }else{
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
