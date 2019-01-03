<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
  // Small hacking class because i was not able to get the id elsehow.
    protected $fillable = [
        'id', 'lang', 'source','media_id'
    ];
    protected $table = 'tracks';
}
