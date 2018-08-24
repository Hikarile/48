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

Route::get('/home', ['as' => 'train_select', 'uses' => 'TrainController@train_select']); //首頁 -列車查詢
Route::get('/home_select/{statopm_s?}/{statopm_e?}/{type?}/{day?}', ['as' => 'train_select_create', 'uses' => 'TrainController@train_select_create']); //首頁 -列車查詢


Route::get('/booking/{s?}/{e?}/{day?}/{train_type?}', ['as' => 'ticket_booking', 'uses' => 'BookingController@ticket_booking']); //預訂車票
Route::post('/booking_create', ['as' => 'booking_create', 'uses' => 'BookingController@booking_create']); //預訂車票


Route::get('/ticket/{booking_id?}/{cellphone?}', ['as' => 'ticket_select', 'uses' => 'BookingController@ticket_select']); //訂票查詢
Route::get('/ticket_del/{id?}', ['as' => 'ticket_del', 'uses' => 'BookingController@ticket_del']); //訂票查詢


Route::get('/train/{train_name?}', ['as' => 'train_data', 'uses' => 'TrainController@train_data']); //列車資訊
Route::get('/train_booking/{train_name?}', ['as' => 'train_booking', 'uses' => 'TrainController@train_booking']); //列車資訊->訂票


Route::get('/login', ['as' => 'login', 'uses' => 'UserController@login']); //登入後台
Route::post('/login_confirm', ['as' => 'login_confirm', 'uses' => 'UserController@login_confirm']);//登入判斷


Route::middleware('login_confirm')->group(function () {
    

    Route::get('/login/type', ['as' => 'type', 'uses' => 'TrainController@type']); //車種管理
    Route::get('/login/type_id/{id?}', ['as' => 'type_id', 'uses' => 'TrainController@type_id']); //修改,新增頁面
    Route::post('/login/type_create', ['as' => 'type_create', 'uses' => 'TrainController@type_create']); //修改,新增資料
    Route::get('/login/type_del/{id}', ['as' => 'type_del', 'uses' => 'TrainController@type_del']); //刪除車種


    Route::get('/login/station', ['as' => 'station', 'uses' => 'TrainController@station']); //車站管理
    Route::get('/login/station/{id?}', ['as' => 'station_id', 'uses' => 'TrainController@station_id']); //修改,新增頁面
    Route::post('/login/station_create', ['as' => 'station_create', 'uses' => 'TrainController@station_create']); //修改,新增資料
    Route::get('/login/station_del/{id}', ['as' => 'station_del', 'uses' => 'TrainController@station_del']); //刪除車種


    Route::get('/login/train', ['as' => 'train', 'uses' => 'TrainController@train']); //列車管理
    Route::get('/login/train_add', ['as' => 'train_add', 'uses' => 'TrainController@train_add']); //新增頁面 
    Route::get('/login/train_fix/{id?}', ['as' => 'train_fix', 'uses' => 'TrainController@train_fix']); //修改頁面 
    Route::post('/login/train_create', ['as' => 'train_create', 'uses' => 'TrainController@train_create']); //新增,修改資料
    Route::get('/login/train_del/{id}', ['as' => 'train_del', 'uses' => 'TrainController@train_del']); //刪除列車


    Route::get('/login/ticket/{train_name?}/{day?}/{cellphone?}/{booking_id?}/{station_s?}/{station_e?}', ['as' => 'ticket', 'uses' => 'BookingController@ticket']); //訂票紀錄查詢


    Route::get('/logout', ['as' => 'logout', 'uses' => 'UserController@logout']); //登出

});