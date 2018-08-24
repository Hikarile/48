<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Account;
use \App\Album;
use \App\Image;

class ImageController extends Controller
{
    use \App\Traits\Process;

    public function store(Request $req)
    {
        $album = $this->valiAlbum($req->account, $req->album_id);
        $image_id = $this->saveImage($req->image);

        $image_data = [
            'album_id' => $album->id,
            'image_id' => $image_id,
            'title' => $req->title,
            'description' => $req->description,
            'width' => getimagesize($req->image)[0],
            'height' => getimagesize($req->image)[1],
            'size' => filesize(Image::getImagePath($image_id)),
        ];

        $image = Image::create($image_data);

        return response()
            ->view('success.image-store', compact('image'), 200)
            ->header('Content-type','application/xml');
    }

    public function saveImage($image, $image_id = '')
    {        
        try {
            if (gettype($image) === 'string') {
                $image = imagecreatefromstring($image);
            } else {
                switch($image->getMimeType()) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($image);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($image);
                        break;
                    case 'image/gif':
                        $image = imagecreatefromgif($image);
                        break;
                    default:
                        $image = imagecreatefromstring($image);
                        break;
                }
            }
        } catch(\Exception $e) {
            return abort(400);
        }

        if (empty($image_id)) {
            $image_id = $this->token(10);
        }else{
            unlink(Image::getImagePath($image_id));
        }
        $quality = 10;
        imagejpeg($image, Image::getImagePath($image_id), $quality);
        imagedestroy($image);

        return $image_id;
    }

    public function show($album_id, $image_id) 
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $image = $album->images()->where('image_id', $image_id)->firstOrFail();

        return response()
                ->view('success.image-show', compact('image'), 200)
                ->header('Content-type', 'application/xml');
    }

    public function update(Request $req, $album_id, $image_id)
    {
        $this->valiAlbum($req->account, $album_id);
        list($album, $image) = $this->valiImage($album_id, $image_id);
        

        $content = $req->getContent();
        $image_update_data = $this->parseContent($content);

        if (isset($image_update_data['image'])) {
            $this->saveImage($image_update_data['image'], $image_id);
            unset($image_update_data['image']);
            $image_update_data['width'] = getimagesize(Image::getImagePath($image_id))[0];
            $image_update_data['height'] = getimagesize(Image::getImagePath($image_id))[1];
            $image_update_data['size'] = filesize(Image::getImagePath($image_id));
        }
        
        $image->update($image_update_data);

        return response()
                ->view('success.image-update', compact('image'), 200)
                ->header('Content-type', 'application/xml');
    }

    public function destroy(Request $req, $album_id, $image_id)
    {
        $this->valiAlbum($req->account, $album_id);
        list($album, $image) = $this->valiImage($album_id, $image_id);
        
        $image->update([
            'delete_token' => $this->token(16),
        ]);

        return response()
                ->view('success.image-delete', ['delete_token' => $image->delete_token], 200)
                ->header('Content-type', 'application/xml');
    }

    public function showImage($image_id, $image_suffix = '')
    {
        $image = Image::where('image_id', $image_id)->firstOrFail();

        $or_image = imagecreatefromjpeg(Image::getImagePath($image->image_id));
        $or_width = $image->width;
        $or_height = $image->height;

        switch ($image_suffix) {
            case '':
                $new_width = $or_width;
                $new_height = $or_height;
                break;
            case 'l':
                $new_width = min(960, $or_width);
                $new_height = min(960, $or_height);
                break;
            case 'm':
                $new_width = min(320, $or_width);
                $new_height = min(320, $or_height);
                break;
            case 's':
                $new_width = min(90, $or_width);
                $new_height = min(90, $or_height);
                break;
            case 't':
                $new_width = 50;
                $new_height = 50;
                break;
            default:
                $new_width = $or_width;
                $new_height = $or_height;
                break;
        }

        if ($image_suffix != 't') {
            if ($or_width > $or_height) {
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

    public function move(Request $req)
    {
        $data = $this->xml2array($req->getContent());
        $account = $req->account;
        $dst_album = $account->albums()->where('album_id', $data['dst_album'])->firstOrFail();

        $albums = $account->albums;
        $pass = false;
        foreach ($albums as $album) {
            if ($album->images()->where('image_id', $data['src_image'])->exists()) {
                $pass = true;
                $image = Image::where('image_id', $data['src_image'])->firstOrFail();
            }
        }
        if(!$pass){
            abort(404);
        }

        $image->update([
            'album_id' => $dst_album->id,
        ]);

        return response()
                ->view('success.show', ['status_code' => 204], 200)
                ->header('content-type', 'application/xml');
    }

    public function parseContent($content)
    {
        $boundary = explode("\r\n", $content)[0];

        $data = [];

        foreach (array_slice(explode($boundary, $content), 1, -1) as $value) {
            $matches = [];

            preg_match('/(?<=name\=")[^"]*(?=")/', $value, $matches);
            $name = $matches[0];

            if ($name != 'title' && $name != 'description' && $name != 'image') {
                continue;
            }

            $value2 = explode("\r\n\r\n", trim($value))[1];
            
            $data[$name] = $value2;
        }
        return $data;
    }
}
