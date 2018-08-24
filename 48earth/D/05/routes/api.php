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

Route::middleware('token')->group(function () {
    Route::post('album', 'AlbumController@add'); //ok
    Route::post('album/{album_id}/image', 'ImageController@add'); //ok

    Route::get('account/{account_id}', 'AccountController@get'); //ok

    Route::patch('album/{album_id}', 'AlbumController@patch'); //ok
    Route::patch('album/{album_id}/images/{image_id}', 'ImageController@patch'); //ok

    Route::delete('album/{album_id}', 'AlbumController@delete'); //ok
    Route::delete('album/{album_id}/images/{image_id}', 'ImageController@delete'); //ok

    Route::post('internal/move-image', 'AlbumController@move'); //ok
    Route::post('internal/undelete-image', 'ImageController@undelete'); //ok
});
Route::post('account', 'AccountController@add'); //ok

Route::get('album/{album_id}', 'AlbumController@get'); //ok
Route::get('album/{album_id}/images/{image_id}', 'ImageController@get'); //ok
Route::get('album/{album_id}/latest', 'AlbumController@latest'); //ok
Route::get('album/{album_id}/hot', 'AlbumController@hot'); //ok

Route::get('album/{album_id}/cover.jpg', 'AlbumController@cover');
Route::get('i/{image_id}{size}.jpg', 'ImageController@photo'); //ok
