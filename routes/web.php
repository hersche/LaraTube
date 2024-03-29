<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Media;
use App\Playlist;
use App\License;
use App\Comment;
use App\Category;
use App\DirectTag;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\License as LicenseResource;
use App\Http\Resources\Playlist as PlaylistResource;
use App\Http\Resources\Media as MediaResource;
use App\Http\Resources\Category as CategoryResource;
use App\User;
use App\Http\Resources\User as UserResource;
//URL::forceScheme('https');
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
if(function_exists("getMediaOrder")==false){
function getMediaOrder($sortByInput){
  $ascDesc = 'desc';
  $sortBy = 'updated_at';
  //$sortByInput = $request->input('sortBy');
  if($sortByInput=="title"){
    $ascDesc = 'asc';
    $sortBy = 'title';
  } else if($sortByInput=="title_reverse"){
    $ascDesc = 'desc';
    $sortBy = 'title';
  }else if($sortByInput=="created_at"){
    $ascDesc = 'asc';
    $sortBy = 'created_at';
  }else if($sortByInput=="created_at_reverse"){
    $ascDesc = 'desc';
    $sortBy = 'created_at';
  }else if($sortByInput=="updated_at"){
    $ascDesc = 'asc';
    $sortBy = 'updated_at';
  }else if($sortByInput=="updated_at_reverse"){
    $ascDesc = 'desc';
    $sortBy = 'updated_at';
  }else if($sortByInput=="type"){
    $ascDesc = 'asc';
    $sortBy = 'type';
  }else if($sortByInput=="type_reverse"){
    $ascDesc = 'desc';
    $sortBy = 'type';
  }
  return [$ascDesc,$sortBy];
};
}
/*
if(config("app.auth")=="local"){
  Auth::routes();
}
*/
Route::get('/', function () {
    return view('base');
});

/*
Route::get('/2fa','PasswordSecurityController@show2faForm');
Route::post('/generate2faSecret','PasswordSecurityController@generate2faSecret')->name('generate2faSecret');
Route::post('/2fa','PasswordSecurityController@enable2fa')->name('enable2fa');
Route::post('/disable2fa','PasswordSecurityController@disable2fa')->name('disable2fa');
Route::post('/verify2FA','HomeController@verify2FA');
// Finish 2fa-login
Route::post('/2faVerify', 'PasswordSecurityController@my2faverify')->name('2faVerify')->middleware('2fa');
// Finaly enable 2fa with the test
Route::post('/internal-api/settings/2faTest', 'PasswordSecurityController@my2faTest')->middleware('2fa');
Route::post('/internal-api/settings/refresh/twofactor', 'PasswordSecurityController@my2faRefresh');
Route::post('/internal-api/settings/get/twofactor', 'PasswordSecurityController@my2faGet');
Route::post('/internal-api/settings/disable/twofactor', 'PasswordSecurityController@my2fadisable');

Route::post('/internal-api/settings/password','UserController@changePassword');
Route::post('/internal-api/users/changeroles', 'UserController@changeRoles');
*/
Route::post('/internal-api/friends/block', 'FriendController@block');
Route::post('/internal-api/friends/unblock', 'FriendController@unblock');
Route::post('/internal-api/friends/friendRequest', 'FriendController@friendRequest');
Route::post('/internal-api/friends/acceptRequest', 'FriendController@acceptRequest');
Route::post('/internal-api/friends/denyRequest', 'FriendController@denyRequest');
Route::post('/internal-api/friends/unfriend', 'FriendController@unfriend');

Route::get('/login', 'OauthClientController@oauthLogin')->name("login");
Route::get('/api/auth/callback', 'OauthClientController@oauthCallback');


Route::get('/api/auth/getOauthUser', 'OauthClientController@oauthGetUser');

Route::get('/api/auth/refreshOauthUser', 'OauthClientController@oauthRefreshUser');

Route::get('/internal-api/import-files', function () {
  $files = Storage::allFiles("import");
  return $files;
});
Route::get('/internal-api/playlists', function () {
  return PlaylistResource::collection(Playlist::where('user_id', '=' ,Auth::id())->orWhere('public',1)->get());
});
Route::get('/internal-api/licenses', function () {
  return LicenseResource::collection(License::all());
});
Route::get('/internal-api/info', function () {
    return response()->json(["data"=>["media_count"=>Media::count(),"can_admin"=>Auth::user()->hasRole('admin')]],200);
});
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
});
Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );
Route::post('/logout', 'Auth\LoginController@logout')->name('logout' );
Route::put('/friends','UserController@changeFriends')->name('friends');


Route::delete('/comment','CommentController@destroy')->name('comments.add');
Route::get('welcome/{locale}', function ($locale) {
    App::setLocale($locale);
    //
});

Route::get('/search', function (Request $request) {
    return App\Media::search($request->search)->get();
});


Route::put('/comment','CommentController@create')->name('comments.add');
Route::post('/comment','CommentController@create')->name('commentsasas.add');
Route::post('/media/create','MediaController@create')->name('medias.create');
Route::put('/like','MediaController@like')->name('media.like');
Route::post('/like','MediaController@like')->name('media222.like');
Route::get('/like','MediaController@like')->name('media222.like');
Route::post('/internal-api/profiles/edit/{id}','UserController@update')->name('useres.edit');
Route::delete('/internal-api/media/{title}','MediaController@destroy')->name('mediasapi.delete');
Route::post('/internal-api/media/{title}','MediaController@edit')->name('mediasiapi.edit');

Route::get('/internal-api/notifications', function (Request $request) {
  if(!empty(Auth::id())){
    return Auth::user()->notifications->toJson();
  }
  return response()->json(["data"=>["msg"=>"No permission"]],200);
});
Route::get('/internal-api/notifications/markasread', function (Request $request) {
  if(!empty(Auth::id())){
    Auth::user()->unreadNotifications->markAsRead();
    return Auth::user()->notifications->toJson();
  }
  return response()->json(["data"=>["msg"=>"No permission"]],200);
});
Route::get('/internal-api/notifications/markasread/{id}', function (Request $request,$id) {
  if(!empty(Auth::id())){
    foreach (Auth::user()->unreadNotifications as $notification) {
      if($notification->id==$id){
        $notification->markAsRead();
      }
    }
    return Auth::user()->notifications->toJson();
  }
  return response()->json(["data"=>["msg"=>"No permission"]],200);
});
Route::get('/internal-api/notifications/delete', function (Request $request) {
  Auth::user()->notifications()->delete();
  //foreach (Auth::user()->notifications as $notification) {
    //$notification->delete();
  //}
  return Auth::user()->notifications->toJson();
});
Route::get('/internal-api/media', 'MediaController@get');

Route::get('/internal-api/medias/all', 'MediaController@getAll');

Route::get('/internal-api/medias/search/{title}', function (Request $request,$title) {
  $title = urldecode($title);
  $sortBy = getMediaOrder($request->input('sortBy'));
  return MediaResource::collection(Media::orderBy($sortBy[1], $sortBy[0])->where('title', 'LIKE' ,'%'.strtoupper($title).'%')->orWhere('title', 'LIKE' ,'%'.strtolower($title).'%')->orWhere('description', 'LIKE' ,'%'.strtoupper($title).'%')->orWhere('description', 'LIKE' ,'%'.strtolower($title).'%')->whereNotIn('id', explode(",",$request->input('i')))->get());
});
Route::get('/internal-api/categories', function (Request $request) {
    return CategoryResource::collection(Category::where("parent_id",0)->get());
});
Route::get('/internal-api/media/{title}', 'MediaController@getByTitle');
Route::get('/internal-api/medias/byId/{id}', 'MediaController@getById');

Route::get('/internal-api/medias/byCatId/{id}', function (Request $request,$id) {
  $sortBy = getMediaOrder($request->input('sortBy'));
  return MediaResource::collection(Media::orderBy($sortBy[1], $sortBy[0])->where('category_id', '=' ,$id)->whereNotIn('id', explode(",",$request->input('i')))->get());
});
Route::get('/internal-api/medias/byCommentId/{id}', function ($id) {
    return new MediaResource(Media::where('id', '=' ,Comment::find($id)->media_id)->firstOrFail());
});
Route::post('/internal-api/media/{id}','MediaController@edit')->name('mediasapi.edit');
Route::delete('/internal-api/media/{id}','MediaController@destroy')->name('mediasapi.delete');
Route::get('/internal-api/medias/by/{user}', function (Request $request,$user) {
    return MediaResource::collection(Media::where('user_id', '=' ,$user)->whereNotIn('id', explode(",",$request->input('i')))->get());
});

Route::post('/internal-api/category','CategoryController@create');
Route::post('/internal-api/category/{id}','CategoryController@edit');
Route::delete('/internal-api/category/{id}','CategoryController@destroy');

Route::post('/internal-api/medias/addTrack','MediaController@addTrack');
Route::post('/internal-api/medias/deleteTrack/{trackid}','MediaController@deleteTrack');
Route::get('/internal-api/refresh-csrf', function(){
  return response()->json(["csrf"=>csrf_token(),"totalMedias"=>Media::count()],200);
});

Route::get('/internal-api/users', function () {

  return UserResource::collection(User::all());
    //return UserResource::collection(User::where("public","=",1)->get());
});
Route::post('/internal-api/users/mkAdmin/{id}','UserController@mkAdmin');
Route::post('/internal-api/users/rmAdmin/{id}','UserController@rmAdmin');
Route::delete('/internal-api/user/{id}','UserController@destroy');
Route::delete('/internal-api/comment/{id}','CommentController@destroy');
Route::get('/internal-api/tags', function () {
    return TagResource::collection(DirectTag::all());
});

Route::get('/internal-api/tags/{tags}', function (Request $request,$tags) {
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
