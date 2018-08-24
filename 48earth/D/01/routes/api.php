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

Route::post('/account', 'UserController@user_add'); //新增使用者
Route::middleware('token')->group(function(){
    Route::get('account/{user_id}', 'UserController@user_get'); //取得使用者資訊
});

Route::middleware('token')->get('account/{user_id}', 'UserController@user_get'); //取得使用者資訊
Route::middleware('token')->post('/album', 'AlbumController@album_add'); //建立相簿
Route::middleware('image')->post('/album/{albumID}/image', 'ImageController@image_add'); //上傳照片
Route::middleware('token')->get('/album/{album_id}', 'AlbumController@album_get'); //取得相簿資訊
Route::middleware('token')->get('/album/{albumID}/latest', 'AlbumController@alabum_get_latest'); //取得最新三張照片
Route::middleware('token')->get('/album/{albumID}/hot', 'AlbumController@album_get_hot'); //取得最多瀏覽的前三張照片
Route::middleware('token')->patch('/album/{albumID}', 'AlbumController@album_path'); //更新相簿資訊
Route::middleware('token')->delete('/album/{albumID}', 'AlbumController@album_delete'); //刪除相簿
Route::middleware('image')->patch('/album/{albumID}/images/{imageID}', 'ImageController@image_patch'); //更新照片
Route::middleware('image')->delete('/album/{albumID}/images/{imageID}', 'ImageController@image_delete'); //刪除照片
Route::get('/album/{albumID}/images/{imageID}', 'ImageController@image_get'); //查詢相簿單一照片
Route::get('/i/{imageID}{imageSuffix}.jpg', 'ImageController@image_see'); //取得照片內容
Route::get('/album/{albumID}/cover.jpg', 'AlbumController@album_see'); //取得相簿封面圖
Route::post('/internal/move-image', 'AlbumController@album_move'); //搬移圖片
Route::post('/internal/undelete-image', 'ImageController@image_recovery'); //回復刪除的圖片

