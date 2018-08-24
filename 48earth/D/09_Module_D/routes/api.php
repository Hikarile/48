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
    Route::post('album', 'AlbumController@add');
    Route::post('album/{album_id}/image', 'ImageController@add');

    Route::get('account/{account_id}', 'AccountController@get');

    Route::patch('album/{album_id}', 'AlbumController@patch');
    Route::patch('album/{album_id}/images/{image_id}', 'ImageController@patch');

    Route::delete('album/{album_id}', 'AlbumController@delete');
    Route::delete('album/{album_id}/images/{image_id}', 'ImageController@delete');

    Route::post('internal/move-image', 'AlbumController@move');
    Route::post('internal/undelete-image', 'ImageController@undelete');

});
Route::post('account', 'AccountController@add');

Route::get('album/{album_id}', 'AlbumController@get');
Route::get('album/{album_id}/latest', 'AlbumController@latest');
Route::get('album/{album_id}/hot', 'AlbumController@hot');
Route::get('album/{album_id}/images/{image_id}', 'ImageController@get');

Route::get('i/{image_id}{size}.jpg', 'ImageController@get_img');
Route::get('album/{album_id}/cover.jpg', 'AlbumController@cover');

