<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Album;
use App\Model\Image;
use Validator;

class AlbumController extends Controller
{
    private function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode(array_slice($all, 0, rand(5, 7)));
    }

    public function album_add(Request $request){
        $xml = simplexml_load_string($request->getContent());
        $array = json_decode(json_encode($xml), true);
        
        $rules = [
            'title' => 'required',
        ];
        $message = [
            'title.required' => '無效的輸入資料',
        ];
        
        $validator = Validator::make($array, $rules, $message);
        if($validator->fails()){
            abort(400, $validator->errors()->first());
        }
        $user = User::where('token', $request->header('Authorization'))->firstOrFail();
        $token = $this->token();
        
        $array['user_id'] = $user->id;
        $array['count'] = 0;
        $array['link'] = 'http://127.0.0.1/XX_Module_F/album/'.$token;
        $array['token'] = $token;
        $array['created'] =time();

        $album = Album::create($array);

        return response()->view('album.album_add', ['token' => $token], 200)
                        ->header('Content-type', 'application/xml');
    }

    public function album_get(Request $request, $album_id){
        $album = Album::where('id', $album_id)->firstOrFail();
        $img = Image::where('album_id', $album_id)->get();
        
        $data = [
            'id' => $album->token,
            'title' => $album->title,
            'description' => $album->description,
            'datetime' => $album->created,
            'account' => $request->header('authorization'),
            'link' => $album->link,
            'images_count' => $album->count,
            'images' => []
        ];
        foreach($img as $val){
            $data['images'][] = [
                "id" =>  $val->tokne,
                "title" =>  $val->title,
                "description" =>  $val->description,
                "width" =>  $val->width,
                "height" =>  $val->height,
                "size" =>  $val->size,
                "views" =>  $val->views,
                "link" =>  $val->link,
                "datetime" =>  $val->created,
            ];
        }

        return response()->view('album.album_get', $data, 200)
                        ->header('Content-type', 'application/xml');
    }

    public function alabum_get_latest(Request $request, $album_id){
        $album = Album::where('id', $album_id)->firstOrFail();
        $img = Image::where('album_id', $album_id)->ORDERBY('id', 'DESC')->get();
        
        $data = [
            'id' => $album->token,
            'title' => $album->title,
            'description' => $album->description,
            'datetime' => $album->created,
            'account' => $request->header('authorization'),
            'link' => $album->link,
            'images_count' => $album->count,
        ];
        foreach($img as $val){
            $data['images'][] = [
                "id" =>  $val->tokne,
                "title" =>  $val->title,
                "description" =>  $val->description,
                "width" =>  $val->width,
                "height" =>  $val->height,
                "size" =>  $val->size,
                "views" =>  $val->views,
                "link" =>  $val->link,
                "datetime" =>  $val->created,
            ];
        }

        return response()->view('album.album_get', $data, 200)
                        ->header('Content-type', 'application/xml');
    }

    public function album_get_hot(Request $request, $album_id){
        $album = Album::where('id', $album_id)->firstOrFail();
        $img = Image::where('album_id', $album_id)->ORDERBY('views', 'DESC')->get();
        
        $data = [
            'id' => $album->token,
            'title' => $album->title,
            'description' => $album->description,
            'datetime' => $album->created,
            'account' => $request->header('authorization'),
            'link' => $album->link,
            'images_count' => $album->count,
        ];
        foreach($img as $val){
            $data['images'][] = [
                "id" =>  $val->tokne,
                "title" =>  $val->title,
                "description" =>  $val->description,
                "width" =>  $val->width,
                "height" =>  $val->height,
                "size" =>  $val->size,
                "views" =>  $val->views,
                "link" =>  $val->link,
                "datetime" =>  $val->created,
            ];
        }

        return response()->view('album.album_get', $data, 200)
                        ->header('Content-type', 'application/xml');
    }

    public function album_path(Request $request, $album_id){
        $xml = simplexml_load_string($request->getContent());
        $array = json_decode(json_encode($xml), true);
        $image = Image::where('album_id', $album_id)->where('delete', '')->get();
        foreach($image as $val){
            $val->cover = 0;
            $val->save();
        }

        $album = Album::where('id', $album_id)->firstOrFail();
        if(!empty($array['covers'])){
            if(is_array($array['covers']['cover'])){
                foreach($array['covers']['cover'] as $val){
                    $image = Image::where('album_id', $album_id)->where('tokne', $val)->where('delete', '')->firstOrFail();
                    $image->cover = 1;
                    $image->save();
                }
            }else{
                $image = Image::where('album_id', $album_id)->where('tokne', $array['covers']['cover'])->where('delete', '')->firstOrFail();
                $image->cover = 1;
                $image->save();
            }
        }if(isset($array['title']) && !empty($array['title'])){
            $album->title = $array['title'];
        }
        if(isset($array['description']) && !empty($array['description'])){
            $album->description = $array['description'];
        }
        $album->save();
        
        return response()->view('album.album_patch', [], 200)
                        ->header('Content-type', 'application/xml');
    }

    public function album_delete(Request $request, $album_id){
        $user =  User::where('token', $request->header('authorization'))->firstOrFail();
        $album = Album::where('user_id', $user->id)->where('id', $album_id)->get();
        foreach($album as $val){
            dd($val);

            $val->delete();
            return response()->view('album.album_delete', [], 200)
                            ->header('Content-type', 'application/xml');
        }
        abort(401, '沒有權限刪除');
        
    }

    public function album_see($album_id){
        $image = Image::where('album_id', $album_id)->where('delete', '')->where('cover', 1)->get();
        $image_copy = imagecreatetruecolor(960, 960);

        switch(count($image)){
            case'1':
                $image_old = imagecreatefromjpeg(base_path('images\\'.$image[0]->image));
                imagecopyresampled($image_copy, $image_old, 0, 0, 0, 0, 960, 960, $image[0]->width, $image[0]->height);
                break;
            case'2':
                $image_old = imagecreatefromjpeg(base_path('images\\'.$image[0]->image));
                imagecopyresampled($image_copy, $image_old, 0, 0, 0, 0, 480, 960, $image[0]->width, $image[0]->height);
                $image_old = imagecreatefromjpeg(base_path('images\\'.$image[1]->image));
                imagecopyresampled($image_copy, $image_old, 480, 0, 0, 0, 480, 960, $image[1]->width, $image[1]->height);
            break;
            case'3':
            $image_old = imagecreatefromjpeg(base_path('images\\'.$image[0]->image));
            imagecopyresampled($image_copy, $image_old, 0, 0, 0, 0, 480, 480, $image[0]->width, $image[0]->height);
            $image_old = imagecreatefromjpeg(base_path('images\\'.$image[1]->image));
            imagecopyresampled($image_copy, $image_old, 480, 0, 0, 0, 480, 480, $image[1]->width, $image[1]->height);
            $image_old = imagecreatefromjpeg(base_path('images\\'.$image[2]->image));
            imagecopyresampled($image_copy, $image_old, 0, 480, 0, 0, 960, 480, $image[2]->width, $image[2]->height);
                break;
        }

        ob_start();
            imagejpeg($image_copy);
            $return = ob_get_contents();
        ob_end_clean();
        
        return response($return, 200)->header('Content-type', 'image/jpeg');
    }

    public function album_move(Request $request){
        $xml = simplexml_load_string($request->getContent());
        $array = json_decode(json_encode($xml), true);
        
        $user = User::where('token', $request->header('authorization'))->firstOrFail();
        $image = Image::where('tokne', $array['src_image'])->where('delete', '')->firstOrFail();
        
        $album_old = Album::where('id', $image->album_id)->where('user_id', $user->id)->firstOrFail();
        $album_old->count -= 1;
        $album_old->save();

        $album_new = Album::where('token', $array['dst_album'])->where('user_id', $user->id)->firstOrFail();
        $album_new->count += 1;
        $album_new->save();
        
        $image->album_id = $album_new->id;
        $image->save();
        
        return response()->view('album.album_move', [], 204)
                        ->header('Content-type', 'application/xml');
    }

}
