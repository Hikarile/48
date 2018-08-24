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

Route::get('/', 'TrainController@index')->name('index');
Route::get('train/{type?}/{day?}/{from?}/{to?}', 'TrainController@train_index')->name('train_index');

Route::get('/booking/{type?}/{day?}/{from?}/{to?}', 'BookingController@index')->name('booking');
Route::post('/booking/create', 'BookingController@create');
Route::get('/ok/{id?}', 'BookingController@ok');

Route::get('/select/{phone?}/{code?}', 'BookingController@select')->name('book_select');
Route::get('/select_delete/{id?}', 'BookingController@delete');

Route::get('/type_select/{code?}', 'TrainController@select')->name('type_select');

Route::middleware('auth')->group(function () {
    Route::get('/login/type', 'TypeController@type')->name('type');
    Route::get('/login/type/add_fix/{id?}', 'TypeController@add_fix');
    Route::post('/login/type/create/{id?}', 'TypeController@create');
    Route::get('/login/type/delete/{id}', 'TypeController@delete');

    Route::get('/login/station', 'StationController@station')->name('station');
    Route::get('/login/station/add_fix/{id?}', 'StationController@add_fix');
    Route::post('/login/station/create/{id?}', 'StationController@create');
    Route::get('/login/station/delete/{id}', 'StationController@delete');

    Route::get('/login/train', 'TrainController@train')->name('train');
    Route::get('/login/train/add', 'TrainController@add_fix');
    Route::get('/login/train/fix/{id?}', 'TrainController@add_fix');
    Route::post('/login/train/create/{id?}', 'TrainController@create');
    Route::get('/login/train/delete/{id}', 'TrainController@delete');

    Route::get('/login/book', 'BookingController@book')->name('book');
});