<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaylistMedia extends Model
{
    //
    protected $fillable = [
        'id', 'media_id', 'playlist_id'
    ];
    protected $table = 'playlist_media';
}
