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
    return MediaResource::collection(Media::orderBy('created_at', 'desc')->paginate(1));
});

Route::get('/media/not/{title}', function ($title) {
    return MediaResource::collection(Media::where('title', '!=' ,$title)->paginate(12));
});

Route::get('/media/{title}', function ($title) {
    return new MediaResource(Media::where('title', '=' ,$title)->firstOrFail());
});

use App\Comment;
use App\Http\Resources\Comment as CommentResource;

Route::get('/comment', function () {
    return CommentResource::collection(Comment::orderBy('created_at', 'desc')->paginate(10));
});
Route::get('/comment/{mediaId}', function ($mediaId) {
    return CommentResource::collection(Comment::where('media_id', '=' ,$mediaId)->orderBy('created_at', 'desc')->paginate(10));
});
