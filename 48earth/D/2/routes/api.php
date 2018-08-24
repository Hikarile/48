<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/account', 'AccountController@store');

Route::get('/album/{album_id}', 'AlbumController@show');
Route::get('/album/{album_id}/lastest', 'AlbumController@showLastest');
Route::get('/album/{album_id}/hot', 'AlbumController@showHot');

Route::get('/album/{album_id}/images/{image_id}', 'ImageController@show');

Route::get('/i/{image_id}{image_suffix}.jpg', 'ImageController@showImage')->where('image_suffix', '[lmst]?')->where('image_id', '.{10}');


Route::get('/album/{album_id}/cover.jpg', 'AlbumController@showCover');

Route::middleware('auth.token')->group(function(){
    Route::patch('/album/{album_id}/images/{image_id}', 'ImageController@update');
    Route::delete('/album/{album_id}/images/{image_id}', 'ImageController@destroy');
    Route::post('/album/{album_id}/image', 'ImageController@store');

    Route::get('/account/{account_id}', 'AccountController@show');

    Route::post('/internal/move-image', 'ImageController@move');
    Route::post('/internal/undelete-image', 'ImageController@undelete');
    
    Route::resource('/album', 'AlbumController');
});