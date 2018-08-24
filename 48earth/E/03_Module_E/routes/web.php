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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'TrainController@lookup')->name('lookup');
Route::get('see/{name?}', 'TrainController@see')->name('see');
Route::get('type/select/{type?}/{day?}/{from?}/{to?}', 'TrainController@select')->name('type_select');

Route::get('booking/{type?}/{day?}/{from?}/{to?}', 'BookingController@booking')->name('booking');
Route::post('booking/create', 'BookingController@create')->name('booking_create');
Route::get('ok/{id?}', 'BookingController@ok')->name('booking_ok');
Route::get('select/{phone?}', 'BookingController@select')->name('booking_select');
Route::get('delete/{id?}', 'BookingController@delete')->name('booking_delete');

Route::middleware('auth')->group(function () {
    Route::get('login/type', 'TypeController@index')->name('type');
    Route::get('login/type/add_fix/{id?}', 'TypeController@add_fix')->name('type_add_fix');
    Route::post('login/type/create/{id?}', 'TypeController@create')->name('type_create');
    Route::get('login/type/delete/{id?}', 'TypeController@delete')->name('type_delete');
    
    Route::get('login/station', 'StationController@index')->name('station');
    Route::get('login/station/add_fix/{id?}', 'StationController@add_fix')->name('station_add_fix');
    Route::post('login/station/create/{id?}', 'StationController@create')->name('station_create');
    Route::get('login/station/delete/{id?}', 'StationController@delete')->name('station_delete');
    
    Route::get('login/train', 'TrainController@index')->name('train');
    Route::get('login/train/add', 'TrainController@add_fix')->name('train_add');
    Route::get('login/train/fix/{id?}', 'TrainController@add_fix')->name('train_fix');
    Route::post('login/train/create/{id?}', 'TrainController@create')->name('train_create');
    Route::get('login/train/delete/{id?}', 'TrainController@delete')->name('train_delete');

    Route::get('login/booking', 'BookingController@log')->name('booking_get');
    Route::get('login/book/delete/{id?}', 'BookingController@remove')->name('remove');
});


