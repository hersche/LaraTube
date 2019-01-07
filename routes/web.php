<?php
use Illuminate\Http\Request;
use App\Media;
use App\Comment;
use App\Category;
use App\DirectTag;
use App\Http\Resources\Media as MediaResource;
use App\Http\Resources\Category as CategoryResource;
use App\User;
use App\Http\Resources\User as UserResource;
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
Route::get('/', function () {
    return view('welcome');
});


Route::get('/internal-api/info', function () {
    return response()->json(["data"=>["media_count"=>Media::count(),"can_admin"=>Auth::user()->can('admin')]],200);
});
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
});
Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );
Route::post('/user/updateAvatar','UserController@updateAvatar')->name('users.updateAvatar');
Route::put('/user/updateAvatar','UserController@updateAvatar')->name('users.updateAvatar');
Route::put('/user/updateBackground','UserController@updateBackground')->name('users.updateBackground');

Route::get('/media/add','MediaController@addMedia')->name('medias.add');
Route::post('/media/create','MediaController@create')->name('medias.create');
Route::put('/media','MediaController@create')->name('mediaasdasds.create');
Route::get('/media/edit/{title}','MediaController@editView')->name('medias.editView');
Route::post('/media/edit/{title}','MediaController@edit')->name('medias.edit');
Route::get('/media/delete/{title}','MediaController@destroy')->name('medias.delete');
Route::get('/media/{title}', 'MediaController@show')->name('media.show');

Route::get('/tags', 'MediaController@tags')->name('tags');
Route::get('/tags/{tags}', 'MediaController@tagsFilter')->name('tags.filter');

Route::post('/directUpload','MediaController@directUpload')->name('medias.directuploadAjax');
Route::put('/directUpload','MediaController@directUpload');
Route::get('/media','MediaController@index')->name('media');
Route::put('/like','MediaController@like')->name('media.like');
Route::post('/like','MediaController@like')->name('media222.like');
Route::get('/like','MediaController@like')->name('media222.like');
Route::get('/friends','UserController@profile')->name('friends');
Route::get('/profile/edit','UserController@selfEdit')->name('users.selfedit');
Route::get('/profile/{name}','UserController@profileview')->name('profile.view');
Route::put('/friends','UserController@changeFriends')->name('friends');
Route::put('/comment','CommentController@create')->name('comments.add');
Route::post('/comment','CommentController@create')->name('commentsasas.add');

Route::delete('/comment','CommentController@destroy')->name('comments.add');
Route::get('welcome/{locale}', function ($locale) {
    App::setLocale($locale);
    //
});

Route::get('/search', function (Request $request) {
    return App\Media::search($request->search)->get();
});

Route::post('/internal-api/profiles/edit/{id}','UserController@update')->name('users.edit');
Route::delete('/internal-api/media/{title}','MediaController@destroy')->name('mediasapi.delete');
Route::post('/internal-api/media/{title}','MediaController@edit')->name('mediasiapi.edit');

Route::get('/internal-api/notifications', function (Request $request) {
  return Auth::user()->notifications->toJson();
});
Route::get('/internal-api/notifications/markasread', function (Request $request) {
  Auth::user()->unreadNotifications->markAsRead();
  return Auth::user()->notifications->toJson();
});
Route::get('/internal-api/notifications/markasread/{id}', function (Request $request,$id) {
  foreach (Auth::user()->unreadNotifications as $notification) {
    if($notification->id==$id){
      $notification->markAsRead();
    }
  }
  return Auth::user()->notifications->toJson();
});
Route::get('/internal-api/notifications/delete', function (Request $request) {
  Auth::user()->notifications()->delete();
  //foreach (Auth::user()->notifications as $notification) {
    //$notification->delete();
  //}
  return Auth::user()->notifications->toJson();
});
Route::get('/internal-api/media', function (Request $request) {
    // var_dump(explode(",",$request->input('i')));
  //  return MediaResource::collection(Media::orderBy('updated_at', 'desc')->whereNotIn('id', explode(",",$request->input('i')))->paginate(3));
  return MediaResource::collection(Media::orderBy('updated_at', 'desc')->paginate(3));
});

Route::get('/internal-api/medias/all', function (Request $request) {
    return MediaResource::collection(Media::orderBy('created_at', 'desc')->whereNotIn('id', explode(",",$request->input('i')))->get());
});

Route::get('/internal-api/medias/search/{title}', function (Request $request,$title) {
    return MediaResource::collection(Media::where('title', 'LIKE' ,'%'.strtoupper($title).'%')->orWhere('title', 'LIKE' ,'%'.strtolower($title).'%')->orWhere('description', 'LIKE' ,'%'.strtoupper($title).'%')->orWhere('description', 'LIKE' ,'%'.strtolower($title).'%')->whereNotIn('id', explode(",",$request->input('i')))->get());
});
Route::get('/internal-api/categories', function (Request $request) {
    return CategoryResource::collection(Category::all());
});
Route::get('/internal-api/media/{title}', function ($title) {
    return new MediaResource(Media::where('title', '=' ,$title)->firstOrFail());
});
Route::get('/internal-api/medias/byId/{id}', function ($id) {
    return new MediaResource(Media::where('id', '=' ,$id)->firstOrFail());
});
Route::get('/internal-api/medias/byCommentId/{id}', function ($id) {
    return new MediaResource(Media::where('id', '=' ,Comment::find($id)->media_id)->firstOrFail());
});
Route::post('/internal-api/media/{id}','MediaController@edit')->name('mediasapi.edit');
Route::delete('/internal-api/media/{id}','MediaController@destroy')->name('mediasapi.delete');
Route::post('/internal-api/login', 'Auth\LoginController@login');

Route::get('/internal-api/medias/by/{user}', function (Request $request,$user) {
    return MediaResource::collection(Media::where('user_id', '=' ,$user)->whereNotIn('id', explode(",",$request->input('i')))->get());
});

Route::post('/internal-api/medias/addTrack','MediaController@addTrack');
Route::post('/internal-api/medias/deleteTrack/{trackid}','MediaController@deleteTrack');
Route::get('/internal-api/refresh-csrf', function(){
    return csrf_token();
});

Route::get('/internal-api/users', function () {
    return UserResource::collection(User::where("public","=",1)->get());
});

Route::delete('/internal-api/user/{id}','UserController@destroy');
Route::delete('/internal-api/comment/{id}','CommentController@destroy');
