<?php

namespace App;
use App\User;
use App\Comment;
use App\Like;
use App\Track;
use Auth;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use \Conner\Tagging\Taggable;
    use Searchable;
    //
    protected $fillable = [
        'id', 'title', 'source','duration','poster_source','intro_start','intro_end','outro_start','outro_end', 'type', 'description', 'user_id','category_id','comments', 'category_id','next_id'
    ];
    protected $hidden = [

    ];
    protected $table = 'medias';

    public function user() {
      return User::find($this->user_id);
    }

    public function category() {
      return Category::find($this->category_id);
    }

    public function tracks() {
      return Track::where("media_id","=",$this->id)->get();
    }
    public function sources() {
      return Track::where("media_id","=",$this->id)->get();
    }
    public function comments() {
    //  ->orWhere('title', 'LIKE' ,'%'.strtolower($title).'%')
      $comments = Comment::where('media_id', '=' ,$this->id)->where("parent_id","=","0")->get()->sortByDesc('created_at');
      return $comments;
    //  return $this->hasMany('App\Comment', 'medias_id')->sortByDesc('created_at');
    }

    public function likeObjects() {
    //  ->orWhere('title', 'LIKE' ,'%'.strtolower($title).'%')
      return Like::where('media_id', '=',$this->id)->get();
    //  return $this->hasMany('App\Comment', 'medias_id')->sortByDesc('created_at');
    }

    public function myLike($request){
      //echo "Auth: ";
      //echo $request->user('api')->id;
      //echo auth()->guard('api')->user()->id;
      //echo " this id ";
      //echo $this->id;
      $like = Like::where('media_id', '=',$this->id)->where('user_id',Auth::id())->first();
      if(empty($like)){
        return "0";
      }
      return $like->count;
    }

    public function likes(){
      $likes = Like::where('media_id', '=',$this->id)->get();
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
      $likes = Like::where('media_id', '=',$this->id)->get();
      $counter = 0;
      foreach($likes as $like){
        //echo "rount";
        if($like->count=="-1"){
          $counter += 1;
        }
      }
      return $counter;
    }
    public function poster(){
      if(empty($this->poster_source)){
        return url("/")."/"."img/404/poster.png";
      }
      return url("/")."/".$this->poster_source;
    }
    public function simpleType(){
      if(($this->type=="directAudio")||($this->type=="localAudio")||($this->type=="torrentAudio")) {
        return "audio";
      }
      return "video";
    }

    public function techType(){
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
        //var_dump($tag);
        if($tag!=NULL){
        $string .= $tag->name." ";
        #	echo $tag->name . ' with url slug of ' . $tag->slug;
      }
    }
      return $string;
    }
}
