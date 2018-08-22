<?php

namespace App;

use App\Media;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hootlex\Friendships\Traits\Friendable;
class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
  use \Conner\Tagging\Taggable;
  use HasRoles;
  use Friendable;
  use Notifiable;
  use SoftDeletes;
  //protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'password', 'bio', 'avatar_source', 'background_source'
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
      return $this->hasMany('App\Media');
    }

    public function avatar(){
      if(empty($this->avatar_source)){
        return "img/404/avatar.png";
      }
      return $this->avatar_source;
    }

    public function background(){
      if(empty($this->background_source)){
        return "img/404/background.png";
      }
      return $this->background_source;
    }
    public function tagString(){
      $string = "";
      foreach($this->tags as $tag) {
        if(!empty($tag)){
        $string .= $tag->name." ";
      }
        #	echo $tag->name . ' with url slug of ' . $tag->slug;
      }
      return $string;
    }

}
