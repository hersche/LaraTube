<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $fillable = [
        'title', 'source', 'type', 'description', 'users_id',
    ];
    protected $hidden = [

    ];
    protected $table = 'medias';

    public function user() {
      return User::find($this->users_id);
    }
}
