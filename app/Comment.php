<?php

namespace App;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';

    protected $fillable = [
        'id','user_id', 'parent_id', 'media_id', 'body', 'user'
    ];
    public function media() {
      return Media::find($this->media_id);
    }
    public function childs() {
      return Comment::where('parent_id', '=' ,$this->id)->get();
      //return Media::find($this->media_id);

    }
    public function user() {
      return $this->belongsTo('App\User');
      //return User::find($this->user_id);
    //  return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function myLike(){
      //echo "Auth: ";
      //echo $request->user('api')->id;
      //echo auth()->guard('api')->user()->id;
      //echo " this id ";
      //echo $this->id;
      $like = Like::where('comment_id', '=',$this->id)->where('user_id',Auth::id())->first();
      if(empty($like)){
        return "0";
      }
      return $like->count;
    }

    public function likes(){
      $likes = Like::where('comment_id', '=',$this->id)->get();
      $counter = 0;
    //  echo "rount";
      //echo $this->id;
      foreach($likes as $like){

        if($like->count=="1"){
          $counter += 1;
        }
      }
      return $counter;
    }

    public function dislikes(){
      $likes = Like::where('comment_id', '=',$this->id)->get();
      $counter = 0;
      foreach($likes as $like){
        //echo "rount";
        if($like->count=="-1"){
          $counter += 1;
        }
      }
      return $counter;
    }

}
