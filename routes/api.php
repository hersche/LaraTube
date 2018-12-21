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
Auth::routes();
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
use App\DirectTag;
use App\Http\Resources\Media as MediaResource;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\Id as Id;

Route::get('/media', function () {
    return MediaResource::collection(Media::orderBy('created_at', 'desc')->paginate(3));
});

Route::get('/media/not/{title}', function ($title) {
    return MediaResource::collection(Media::where('title', '!=' ,$title)->paginate(12));
});

Route::get('/media/{title}', function ($title) {
    return new MediaResource(Media::where('title', '=' ,$title)->firstOrFail());
});

Route::get('/media/by/{title}', function ($title) {
    return MediaResource::collection(Media::where('user_id', '!=' ,$title)->get());
});

Route::get('/tags', function () {
    return TagResource::collection(DirectTag::all());
});
Route::get('/tags/{tags}', function (Request $request,$tags) {
  $tagArrayExtract = explode(' ', $tags);
  $tagArray = array();
  foreach($tagArrayExtract as $tag){
    if(starts_with($tag, '#')){
      array_push($tagArray, substr($tag,1));
    } else {
      array_push($tagArray, $tag);
    }
  }
  if($request->input('tagsCombine')=="true"){
    $medias = Media::withAllTags($tagArray)->paginate(12);
  } else {
    $medias = Media::withAnyTag($tagArray)->paginate(12);
  }
    return MediaResource::collection($medias);
});
use App\Comment;
use App\Http\Resources\Comment as CommentResource;

Route::get('/comment', function () {
    return CommentResource::collection(Comment::orderBy('created_at', 'desc')->paginate(10));
});
Route::get('/comment/{mediaId}', function ($mediaId) {
    return CommentResource::collection(Comment::where('media_id', '=' ,$mediaId)->orderBy('created_at', 'desc')->paginate(10));
});
