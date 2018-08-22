<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';

    protected $fillable = [
        'id','user_id', 'media_id', 'body'
    ];
    public function media() {
      return Media::find($this->media_id);
    }
    public function user() {
      return User::find($this->user_id);
    }
}
