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

Route::get('/', 'TrainController@lop')->name('index');
Route::get('/select/{type?}/{day?}/{from?}/{to?}', 'TrainController@select');

Route::get('booking/{code?}/{day?}/{from?}/{to?}', 'BookController@booking')->name('booking');
Route::post('book/create', 'BookController@create');
Route::get('book/ok/{id?}', 'BookController@ok');

Route::get('book/select', 'BookController@select')->name('select');
Route::get('book_delete/{id?}', 'BookController@delete');

Route::get('see/{code?}', 'TrainController@see')->name('see');

Route::middleware('auth')->group(function () {
    Route::get('login/type', 'TypeController@index')->name('type');
    Route::get('login/type/add_fix/{id?}', 'TypeController@add_fix');
    Route::post('login/type/create/{id?}', 'TypeController@create');
    Route::get('login/type/delete/{id?}', 'TypeController@delete');

    Route::get('login/station', 'StationController@index')->name('station');
    Route::get('login/station/add_fix/{id?}', 'StationController@add_fix');
    Route::post('login/station/create/{id?}', 'StationController@create');
    Route::get('login/station/delete/{id?}', 'StationController@delete');

    Route::get('login/train', 'TrainController@index')->name('train');
    Route::get('login/train/add', 'TrainController@add');
    Route::get('login/train/fix/{id?}', 'TrainController@fix');
    Route::post('login/train/create/{id?}', 'TrainController@create');
    Route::get('login/train/delete/{id?}', 'TrainController@delete');
    
    Route::get('login/book', 'BookController@index')->name('book');
});
