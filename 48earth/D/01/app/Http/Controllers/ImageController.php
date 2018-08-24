<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Album;
use App\Model\Image;
use Validator;

class ImageController extends Controller
{
    private function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode(array_slice($all, 0,10));
    }
    private function token_delete(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode(array_slice($all, 0, 16));
    }

    public function image_add(Request $request, $album_id){
        $rules = [
           'title' => 'required',
            'image' => 'required|image',
        ];
        $messages = [
            'title.required' => '輸入無效資料',
            'image.required' => '輸入無效資料',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }

        $album = Album::where('id', $album_id)->get();
        foreach($album as $val){
            $val->count += 1;
            $val->save();
        }

        switch ($request->image->getmimeType()){
            case 'image/png':
                $image_data = imagecreatefrompng($request->image);
                break;
            case 'image/jpeg':
                $image_data = imagecreatefromjpeg($request->image);
                break;
            case 'image/gif':
                $image_data = imagecreatefromgif($request->image);
                break;
        }

        $filename = uniqid(rand()).'.jpg';
        $url = base_path("images\\".$filename);
        imagejpeg($image_data, $url, 40);
        imagedestroy($image_data);

        $width = getimagesize($url)[0];
        $height = getimagesize($url)[1];
        $size = filesize($url);

        $data = [
            'album_id' => $album_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filename,
            'width' => $width,
            'height' => $height,
            'size' => $size,
            'views' => 0,
            'cover' => 0,
            'link' => 'http://127.0.0.1/XX_Module_F/i/'.$filename,
            'tokne' => $this->token(),
            'created' => time(),
            'delete' => ''
        ];

        $image = Image::create($data);
        $d = [
            'datetime'=>$image->created,
            'width'=>$image->width,
            'height'=>$image->height,
            'size'=>$image->size
        ];
        return response()->view('image.image_add', $d ,200)
                        ->header('Content-type', 'application/xml');
    }

    public function image_get($album_id, $image_id){
        $image = Image::where('album_id', $album_id)->where('id', $image_id)->where('delete', '')->firstOrFail();
        $image->views +=1;
        $image->save();
        
        $data = [
            "id" => $image->token,
            "title" => $image->title,
            "description" => $image->description,
            "datetime" => $image->created,
            "width" => $image->width,
            "height" => $image->height,
            "size" => $image->size,
            "views" => $image->views,
            "link" => $image->link,
        ];
        
        return response()->view('image.image_get', $data, 200)
                        ->header('Content-type', 'application/xml');
    }

    public function image_patch(Request $request, $album_id, $image_id){
        $image = Image::where('id', $image_id)->where('album_id', $album_id)->where('delete', '')->firstOrFail();
        $data = $this->parseContent($request->getContent()); 

        if(isset($data['title'])){
           $image->title = $data['title'];
        }if(isset($data['description'])){
            $image->description = $data['description'];
        }if(isset($data['image'])){
            $url = base_path('images\\'.$image->image);
            unlink($url);

            $filename = uniqid(rand()).'.jpg';
            $url = base_path('images\\'.$filename);
            $image_data = imagecreatefromstring($data['image']);
            imagejpeg($image_data, $url, 40);

            $width = getimagesize($url)[0];
            $hieght = getimagesize($url)[1];
            $size = filesize($url);

            $image->image = $filename;
            $image->width = $width;
            $image->height = $hieght;
            $image->size = $size;
            $image->link = "http://127.0.0.1/XX_Module_F/i/".$filename;
        }
        $image->save();

        $data = [
            "id" => $image->token,
            "datetime" => $image->datetime,
            "width" => $image->width,
            "height" => $image->height,
            "size" => $image->size,
            "link" => $image->link,
        ];
        return response()->view('image.image_add', $data ,200)
                        ->header('Content-type', 'application/xml');
    }

    public function image_see($image_id, $imageSuffix){
        if(strlen($image_id) < 10){
            $image_id = $image_id.$imageSuffix;
            $imageSuffix = '';
        }
        if(!in_array($imageSuffix, ['', 't', 's', 'm', 'l'])){
            abort(400, '無效的輸入資料');
        }

        $image = Image::where('tokne', $image_id)->where('delete', '')->firstOrFail();

        $url = base_path('images\\'.$image->image);
        $image_old = imagecreatefromjpeg($url);

        switch($imageSuffix){
            case '':
                $width = $image->width;
                $height = $image->height;
                break;
            case 't':
                $width = 50;
                $height = 50;
                break;
            case 's':
                $width = min(90, $image->width);
                $height = min(90, $image->height);
                break;
            case 'm':
                $width = min(320, $image->width);;
                $height = min(320, $image->height);;
                break;
            case 'l':
                $width = min(960, $image->width);;
                $height = min(960, $image->height);;
                break;
        }
        $x = 0; $y = 0;
        if($image->width > $width){
            $x = ($image->width - (($image->width - $width)/2) - $width);
        }if($image->height > $height){
            $y = ($image->height - (($image->height - $height)/2) - $height);
        }

        $image_copy = imagecreatetruecolor($width, $height);
        imagecopy($image_copy, $image_old, 0, 0, $x, $y, $width, $height);

        ob_start();
            imagejpeg($image_copy);
            $return = ob_get_contents();
        ob_end_clean();
        return response($return, 200)->header('content-type', 'image/jpeg');

    }

    public function image_delete($album_id, $image_id){
        $image = Image::where('id', $image_id)->where('album_id', $album_id)->where('delete', '')->firstOrFail();
        $image->delete = $this->token_delete;
        $image->save();
        return respose()->view('image.image_delete', ['delete' => $image->delete], 200)
                        ->header('Content-type', 'application/xml');
    }

    public function image_recovery(Request $request){
        //dd($request->getContetn());
        $xml = simplexml_load_string($request->getContent());
        $array = json_decode(json_encode($xml), true);
        //dd($array);
        $image = Image::where('id', $array['dst_album'])->where('delete', '!=', '')->firstOrFail();
        $image->delete = '';
        $image->save();

        return respose()->view('album.album_move', [], 200)
                        ->header('Content-type', 'application/xml');
    }
    
}

