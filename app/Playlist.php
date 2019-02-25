<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PlaylistMedia;
class Playlist extends Model
{
    //
    
    protected $fillable = [
        'id', 'title', 'description','public','user_id'
    ];
    protected $table = 'playlists';
    
    public function media_ids() {
      return PlaylistMedia::where("playlist_id","=",$this->id)->value('media_id');
    }
}
