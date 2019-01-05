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
//Route::get('/register', 'ManagersController@create');
//Route::post('/register', 'ManagersController@store');
//Route::get('/licenses/', 'ModeratorController@index');
//Route::post('/licenses/{id}/process', 'ModeratorController@process');

Route::get('/', function (){
   return view('welcome');
});

Route::get('vue', function (){
    return view('vue');
});