<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Account;
use \App\Album;
use \App\Image;


class AlbumController extends Controller
{
    use \App\Traits\Process;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->xml2array($request->getContent());
        $account = $request->account;
    
        $album = Album::create([
            'account_id' => $account->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'album_id' => $this->token(rand(5, 11)),
        ]);

        return response()
                ->view('success.show', [
                        'status_code' => 200,
                        'message' => $album->album_id,
                ], 200)
                ->header('Content-type', 'application/xml');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $account = Account::find($album->account_id);
        $images = $album->images;

        return response()
                ->view('success.album-show', compact('account', 'album', 'images'), 200)
                ->header('Content-type', 'application/xml');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $album_id)
    {
        $album = $this->valiAlbum($request->account, $album_id);
        
        $data = $this->xml2array($request->getContent());
        
        if (!empty($data['covers'])) {
            if (!empty($data['covers']['cover'])) {
                if (is_array($data['covers']['cover'])) {
                    if (count($data['covers']['cover']) > 3) {
                        abort(400, 'Covers to many');
                    }
                    $data['covers'] = implode(',', $data['covers']['cover']);
                }else{
                    $data['covers'] = $data['covers']['cover'];
                }
            }else{
                $data['covers'] = '';
            }
        }
        
        $album->update($data);

        return response()
                ->view('success.show', ['status_code' => 200], 200)
                ->header('Content-type', 'application/xml');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req, $album_id)
    {
        $album = $this->valiAlbum($req->account, $album_id);

        foreach ($album->images as $image) {
            unlink(Image::getImagePath($image->image_id));
        } 
        $album->images()->delete();
        $album->delete();

        return response()
                ->view('success.show', ['status_code' => 200], 200)
                ->header('Content-type', 'application/xml');
    }

    public function showLastest($album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $account = Account::find($album->account_id);
        $images = $album->images()->orderBy('created_at', 'desc')->limit(3)->get();

        return response()
                ->view('success.album-show-2', compact('account', 'album', 'images'), 200)
                ->header('Content-type', 'application/xml');
    }

    public function showHot($album_id)
    {
        $album = Album::where('album_id', $album_id)->firstOrFail();
        $account = Account::find($album->account_id);
        $images = $album->images()->orderBy('views', 'desc')->limit(3)->get();

        return response()
                ->view('success.album-show-2', compact('account', 'album', 'images'), 200)
                ->header('Content-type', 'application/xml');
    }

    public function showCover($album_id)
    {
        $covers = explode(',', Album::where('album_id', $album_id)->firstOrFail()->covers);
        
        $images = [];
        foreach ($covers as $cover) {
            $image = Image::where('image_id', $cover)->firstOrFail();
            $images[] = [
                'width' => $image->width,
                'height' => $image->height,
                'image_id' => $image->image_id,
            ];
        }

        $new_image = imagecreatetruecolor(90, 90);

        switch (count($images)) {
            case 1:
                $image = imagecreatefromjpeg(Image::getImagePath($images[0]['image_id']));
                if ($images[0]['width'] > $images[0]['height']) {
                    $h = $images[0]['height'];
                    $w = $images[0]['height'];
                } else {
                    $h = $images[0]['width'];
                    $w = $images[0]['width'];
                }
                imagecopyresized($new_image, $image, 0, 0, $images[0]['width'] / 2 - $w / 2, $images[0]['height'] / 2 - $h / 2, 90, 90, $w, $h);
                break;
            case 2:
                for ($i = 0; $i <= 1; $i++) {
                    $image = imagecreatefromjpeg(Image::getImagePath($images[$i]['image_id']));
                    if ($images[$i]['height'] > $images[$i]['width'] * 2) {
                        $h = $images[$i]['width'] * 2;
                        $w = $images[$i]['width'];
                    } else {
                        $h = $images[$i]['height'];
                        $w = $images[$i]['height'] / 2;
                    }
                    imagecopyresized($new_image, $image, $i * 45, 0, $images[$i]['width'] / 2 - $w / 2, $images[$i]['height'] / 2 - $h / 2, 45, 90, $w, $h);
                }
                break;
            case 3:
                $image = imagecreatefromjpeg(Image::getImagePath($images[0]['image_id']));
                if ($images[0]['width'] > $images[0]['height']) {
                    $h = $images[0]['height'];
                    $w = $images[0]['height'];
                } else {
                    $h = $images[0]['width'];
                    $w = $images[0]['width'];
                }
                imagecopyresized($new_image, $image, 0, 0, $images[0]['width'] / 2 - $w / 2, $images[0]['height'] / 2 - $h / 2, 90, 90, $w, $h);

                for ($i = 1; $i <= 2; $i++) {
                    $image = imagecreatefromjpeg(Image::getImagePath($images[$i]['image_id']));
                    if ($images[$i]['width'] > $images[$i]['height']) {
                        $h = $images[$i]['height'];
                        $w = $images[$i]['height'];
                    } else {
                        $h = $images[$i]['width'];
                        $w = $images[$i]['width'];
                    }

                    imagecopyresized($new_image, $image, ($i-1) * 45, 45, $images[$i]['width'] / 2 - $w / 2, $images[$i]['height'] / 2 - $h / 2, 45, 45, $w, $h);
                }
                
                break;
        }

        ob_start();
        imagejpeg($new_image);
        $content = ob_get_contents();
        ob_end_clean();

        return response($content)->header('content-type','image/jpeg');
    }
}
