<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    protected $fillable = [
        'id', 'user_id', 'media_id','comment_id','count'
    ];
        protected $table = 'likes';
}
