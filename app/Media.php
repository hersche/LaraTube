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
        'id', 'title','poster_source','description', 'user_id','category_id','comments', 'category_id','next_id', 'base_type'
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
    //  return Track::where("media_id","=",$this->id)->get();
      return $this->hasMany('App\MediaSource');
    }
    public function comments() {
    //  ->orWhere('title', 'LIKE' ,'%'.strtolower($title).'%')
      //$comments = Comment::where('media_id', '=' ,$this->id)->where("parent_id","=","0")->get()->sortByDesc('created_at');
      //return $comments;
      return $this->hasMany('App\Comment')->where('parent_id',0)->orderBy('created_at');
    }
    public function view()
    {
        return $this->hasOne('App\MediaView', 'user_id',Auth::id());
    }
    public function license()
    {
        return $this->hasOne('App\License');
    }
    public function totalView(){
      return $this->hasMany('App\MediaView')->sum('seconds');
    }
    public function chapters()
    {
        return $this->hasMany('App\MediaChapter');
    }
    public function myLike($request){
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
