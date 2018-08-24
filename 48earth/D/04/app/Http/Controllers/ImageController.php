<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Account;
use App\Model\Album;
use App\Model\Image;

class ImageController extends Controller
{
    public function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 4, 10));
    }
    public function delete_token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 4,16));
    }

    public function add(Request $request, $album_id){
        $name = $this->image($request->image);
        $url = base_path('images\\'.$name);

        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['image_id'] = $this->token();
        $data['name'] = $name;
        $data['width'] = getimagesize($url)[0];
        $data['height'] = getimagesize($url)[1];
        $data['size'] = filesize($url);
        $data['view'] = 0;
        $data['delete'] = "";
        
        $image = $request->account->albums()->where('album_id', $album_id)->firstOrFail()->images()->create($data);
        return response()->view('image.add', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function get(Request $request, $album_id, $image_id){
        $image = $request->account->albums()->where('album_id', $album_id)->firstOrFail()->images()->where('image_id', $image_id)->firstOrFail();
        $image->view_add();
        return response()->view('image.get', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function delete(Request $request, $album_id, $image_id){
        $image = $request->account->albums()->where('album_id', $album_id)->firstOrFail()->images()->where('image_id', $image_id)->firstOrFail();
        $image->delete = $this->delete_token();
        $image->save();
        return response()->view('image.delete', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function patch(Request $request, $album_id, $image_id){
        $data = $this->slice($request->getContent());
        $name = $this->image($data['name']);
        $url = base_path('images\\'.$name);

        $data['name'] = $name;
        $data['width'] = getimagesize($url)[0];
        $data['height'] = getimagesize($url)[1];
        $data['size'] = filesize($url);

        $image = $request->account->albums()->where('album_id', $album_id)->firstOrFail()->images()->where('image_id', $image_id)->firstOrFail();
        unlink(base_path('images\\'.$image->name));
        $image->update($data);
        return response()->view('image.patch', compact('image'), 200)->header('Content-Type', 'application/xml');
    }

    public function undelete(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $album = $request->account->albums()->where('album_id', $data['dst_album'])->firstOrFail();
        $image = Image::where('album_id', $album->id)->where('delete', $data['delete_token'])->firstOrFail();
        $image->delete ="";
        $image->save();
        return response()->view('move')->header('Content-Type', 'application/xml');
    }

    public function photo(Request $request, $image_id, $size){
        if(strlen($image_id) != 10){
            $image_id .= $size;
            $size = "";
        }
        $image = Image::where('image_id', $image_id)->firstOrFail();
        $image->view_add();
        
        $or_image = imagecreatefromjpeg(base_path('images\\'.$image->name));
        $or_width = $image->width;
        $or_height = $image->height;
        
        switch($size){
            case"":
                $new_width = $or_width;
                $new_height = $or_height;
            break;
            case"l":
                $new_width = min(960, $or_width);
                $new_height = min(960, $or_height);
            break;
            case"m":
                $new_width = min(320, $or_width);
                $new_height = min(320, $or_height);
            break;
            case"s":
                $new_width = min(90, $or_width);
                $new_height = min(60, $or_height);
            break;
            case"t":
                $new_width = 50;
                $new_height = 50;
            break;
            default:
            return abort(400);
        }

        if($size != "t"){
            if($or_width > $or_height){
                $new_height = $or_height * ($new_width / $or_width);
            }else{
                $new_width = $or_width * ($new_height / $or_height);
            }
        }
        
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresized($new_image, $or_image, 0, 0, 0, 0, $new_width, $new_height, $or_width, $or_height);

        ob_start();
        imagejpeg($new_image);
        $content = ob_get_contents();
        ob_end_clean();
        return response($content)->header('Content-type', 'image/jpeg');
    }

    public function slice($image){
        $title = explode("\r\n", $image)[0];
        $data = [];
        foreach(array_slice(explode($title, $image), 1, -1) as $val){
            $key = trim(explode("=", explode(";", explode("\r\n", $val)[1])[1])[1], '"');
            if($key == "image"){
                $data["name"] = $val = explode("\r\n", $val)[4];
            }else{
                $data[$key] = $val = explode("\r\n", $val)[3];
            }
            
        }
        return $data;
    }
    public function image($image){
        try{
            if(gettype($image) == "string"){
                $image = imagecreatefromstring($image);
            }else{
                switch($image->getClientmimeType()){
                    case"image/jpeg":
                        $image = imagecreatefromjpeg($image);
                    break;
                    case"image/png":
                        $image = imagecreatefrompng($image);
                    break;
                    case"image/gif":
                        $image = imagecreatefromgif($image);
                    break;
                    default:
                    throw new \Exception;
                }
            }
        } catch(\Exception $e){
            return abort(400);
        }

        $name = uniqid(rand()).".jpg";
        $url = base_path('images\\'.$name);
        imagejpeg($image, $url, 40);
        return $name;
    }
}
