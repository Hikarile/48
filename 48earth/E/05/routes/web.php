<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'TrainController@select')->name('index');
Route::get('/train_see/{type?}/{day?}/{from?}/{to?}', 'TrainController@see');

Route::get('/booking/{type?}/{day?}/{from?}/{to?}', 'BookController@booking')->name('booking');
Route::post('/book_create', 'BookController@create');
Route::get('/book_ok/{id?}', 'BookController@ok');

Route::get('/book_select', 'BookController@select')->name('book_select');
Route::get('/book_delete/{id?}', 'BookController@delete');

Route::get('/train_select/{code?}', 'TrainController@log')->name('train_select');

Route::middleware('auth')->group(function () {
    Route::get('/login/type', 'TypeController@index')->name('login_type');
    Route::get('/login/type/add_fix/{id?}', 'TypeController@add_fix');
    Route::post('/login/type/create/{id?}', 'TypeController@create');
    Route::get('/login/type/delete/{id?}', 'TypeController@delete');

    Route::get('/login/station', 'StationController@index')->name('login_station');
    Route::get('/login/station/add_fix/{id?}', 'StationController@add_fix');
    Route::post('/login/station/create/{id?}', 'StationController@create');
    Route::get('/login/station/delete/{id?}', 'StationController@delete');

    Route::get('/login/train', 'TrainController@index')->name('login_train');
    Route::get('/login/train/add', 'TrainController@add');
    Route::get('/login/train/fix/{id?}', 'TrainController@fix');
    Route::post('/login/train/create/{id?}', 'TrainController@create');
    Route::get('/login/train/delete/{id?}', 'TrainController@delete');
    
    Route::get('/login/book', 'BookController@index')->name('login_book');
});