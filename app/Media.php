<?php

namespace App;
use App\User;
use App\Comment;
use App\Like;
use Auth;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use \Conner\Tagging\Taggable;
    use Searchable;
    //
    protected $fillable = [
        'id', 'title', 'source','duration','poster_source', 'type', 'description', 'user_id','comments',
    ];
    protected $hidden = [

    ];
    protected $table = 'medias';

    public function user() {
      return User::find($this->users_id);
    }

    public function comments() {
      $media = Comment::where('media_id', '=' ,$this->id)->get()->sortByDesc('created_at');
      return $media;
    //  return $this->hasMany('App\Comment', 'medias_id')->sortByDesc('created_at');
    }

    public function myLike(){
      $like = Like::where('media_id', '=',$this->id)->where('user_id',Auth::id())->first();
      if(empty($like)){
        return "0";
      }
      return $like->count;
    }

    public function likes(){
      $likes = Like::where('media_id', '=',$this->id)->get();
      $counter = 0;
      foreach($likes as $like){
        if($like->count=="1"){
          $counter += 1;
        }
      }
      return $counter;
    }

    public function dislikes(){
      $likes = Like::where('media_id', '=',$this->id)->get();
      $counter = 0;
      foreach($likes as $like){
        if($like->count=="-1"){
          $counter += 1;
        }
      }
      return $counter;
    }
    public function poster(){
      if(empty($this->poster_source)){
        return "img/404/image.png";
      }
      return $this->poster_source;
    }
    public function simpleType(){
      if(($this->type=="torrentAudio")||($this->type=="torrentVideo")) {
        return "torrent";
      } else if(($this->type=="directAudio")||($this->type=="localAudio")) {
        return "audio";
      }
      return "video";
    }

    public function tagString(){
      $string = "";
      foreach($this->tags as $tag) {
        $string .= $tag->name." ";
        #	echo $tag->name . ' with url slug of ' . $tag->slug;
      }
      return $string;
    }
}
