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

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
});

Auth::routes();

Route::get('/media/{title}', 'MediaController@show')->name('media.show');
Route::get('/directUpload','MediaController@index');
Route::get('/profileEdit','UserController@selfEdit');
Route::post('/directUpload','MediaController@directUpload');
