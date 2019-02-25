<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    //
    protected $fillable = [
        'id', 'title', 'short_description','description'
    ];
    protected $table = 'licenses';
}
