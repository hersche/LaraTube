<?php

use Illuminate\Http\Request;
//use Auth;
use App\User;
use App\Http\Resources\User as UserResource;
use App\Media;
use App\DirectTag;
use App\Http\Resources\Media as MediaResource;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\Id as Id;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;
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
//Route::post('login', 'Auth\LoginController@login');
//Route::post('register', 'API\RegisterController@register');
// Auth::routes();
//URL::forceScheme('https');

//if(config("app.auth")=="local"){
  Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
  })->middleware('scope:profile');
//}

/*
Route::get('/user', function () {
    return UserResource::collection(User::where("public","=",1)->get());
});

Route::get('/user/{id}', function ($id) {
    return new UserResource(User::find($id));
});
*/


Route::get('/media', function (Request $request) {
    // var_dump(explode(",",$request->input('i')));
    return MediaResource::collection(Media::orderBy('updated_at', 'desc')->whereNotIn('id', explode(",",$request->input('i')))->paginate(3));
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

Route::get('/medias/all', function (Request $request) {
    return MediaResource::collection(Media::orderBy('created_at', 'desc')->whereNotIn('id', explode(",",$request->input('i')))->get());
});


Route::post('/medias/create','MediaController@create')->name('mediasapi.create');
Route::delete('/media/{title}','MediaController@destroy')->name('mediasapi.delete');
Route::post('/media/{title}','MediaController@edit')->name('mediasapi.edit');
Route::get('/media/search/{title}', function ($title) {
    return MediaResource::collection(Media::where('title', 'LIKE' ,'%'.$title.'%')->orWhere('description', 'LIKE' ,'%'.$title.'%')->whereNotIn('id', explode(",",$request->input('i')))->get());
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
    $medias = Media::withAllTags($tagArray)->get();
  } else {
    $medias = Media::withAnyTag($tagArray)->get();
  }
    return MediaResource::collection($medias);
});


Route::get('/comment', function () {
    return CommentResource::collection(Comment::orderBy('created_at', 'desc')->paginate(10));
});
Route::get('/comment/{mediaId}', function ($mediaId) {
    return CommentResource::collection(Comment::where('media_id', '=' ,$mediaId)->orderBy('created_at', 'desc')->paginate(10));
});
