<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $fillable = [
        'title', 'source', 'type', 'description',
    ];
    protected $hidden = [
        'users_id',
    ];
}
