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

Route::middleware('token')->group(function(){
    Route::get('account/{account_id}', 'AccountController@account_get');
    Route::post('album', 'AlbumController@albumAdd');
    Route::post('album/{album_id}/image', 'ImageController@image_add');
    Route::patch('album/{album_id}', 'AlbumController@albumPatch');
    Route::delete('album/{album_id}', 'AlbumController@albumDelete');
    Route::get('album/{album_id}/images/{image_id}', 'ImageController@image_one');

    Route::patch('album/{album_id}/images/{image_id}', 'ImageController@patch');
    Route::delete('album/{album_id}/images/{image_id}', 'ImageController@delete');

    Route::post('internal/move-image', 'ImageController@move');
    Route::post('internal/undelete-image', 'ImageController@undelete');
});

Route::post('account', 'AccountController@account_add');
Route::get('album/{album_id}', 'AlbumController@albumGet');
Route::get('album/{album_id}/latest', 'AlbumController@albumLatest');
Route::get('album/{album_id}/hot', 'AlbumController@albumHot');

Route::get('i/{image_id}{size}.jpg', 'ImageController@image_get');
Route::get('album/{album_id}/cover.jpg', 'AlbumController@cover');

