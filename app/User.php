<?php

namespace App;

use App\Media;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
  use \Conner\Tagging\Taggable;
  use HasRoles;
  //protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'password', 'avatar_source', 'background_source'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function medias(){
      $media = Media::where('users_id', '=' ,$this->id)->get();
      return $media;
    }

    public function tagString(){
      $string = "";
      foreach($this->tags as $tag) {
        $string .= $tag->name." ";
        #	echo $tag->name . ' with url slug of ' . $tag->slug;
      }
      return $string;
    }

}
