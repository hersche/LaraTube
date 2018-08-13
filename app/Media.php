<?php

namespace App;
use App\User;
use App\Comment;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use \Conner\Tagging\Taggable;
    //
    protected $fillable = [
        'title', 'source','duration','poster_source', 'type', 'description', 'users_id',
    ];
    protected $hidden = [

    ];
    protected $table = 'medias';

    public function user() {
      return User::find($this->users_id);
    }

    public function comments() {
      $media = Comment::where('medias_id', '=' ,$this->id)->get();
      return $media;
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
