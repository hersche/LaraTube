<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $fillable = [
        'title', 'source','poster_source', 'type', 'description', 'users_id',
    ];
    protected $hidden = [

    ];
    protected $table = 'medias';

    public function user() {
      return User::find($this->users_id);
    }

    public function simpleType(){
      if(($this->type=="torrentAudio")||($this->type=="torrentVideo")) {
        return "torrent";
      } else if(($this->type=="directAudio")||($this->type=="localAudio")) {
        return "audio";
      }
      return "video";
    }
}
