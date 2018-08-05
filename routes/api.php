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
Route::post('register', 'API\RegisterController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
use App\User;
use App\Http\Resources\User as UserResource;

Route::get('/user', function () {
    return UserResource::collection(User::all());
});

Route::get('/user/{id}', function ($id) {
    return new UserResource(User::find($id));
});

use App\Media;
use App\Http\Resources\Media as MediaResource;

Route::get('/media', function () {
    return MediaResource::collection(Media::all());
});

Route::get('/media/not/{title}', function ($title) {
    return MediaResource::collection(Media::where('title', '!=' ,$title)->get());
});

Route::get('/media/{title}', function ($title) {
    return new MediaResource(Media::where('title', '=' ,$title)->firstOrFail());
});
