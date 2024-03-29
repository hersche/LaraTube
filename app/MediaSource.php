<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaSource extends Model
{
    //
    protected $fillable = ['media_id', 'title', 'source_type', 'duration', 'source', 'type'];

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
}
