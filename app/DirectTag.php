<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DirectTag extends Model
{
  // Small hacking class because i was not able to get the id elsehow.
    protected $fillable = [
        'id', 'slug', 'name','suggest','count'
    ];
    protected $table = 'tagging_tags';
}
