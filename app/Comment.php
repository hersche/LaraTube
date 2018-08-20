<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';

    protected $fillable = [
        'id','users_id', 'medias_id', 'body'
    ];
    public function media() {
      return Media::find($this->medias_id);
    }
    public function user() {
      return User::find($this->users_id);
    }
}
