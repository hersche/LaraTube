<?php

namespace App;

use App\Media;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FriendableTempFix;
class User extends Authenticatable
{
  use \Conner\Tagging\Taggable;
  use FriendableTempFix;
  use Notifiable;
  use SoftDeletes;
  //protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','username','roles', 'email','public', 'bio', 'avatar', 'background'
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

    public function passwordSecurity()
    {
      $ps = $this->hasOne('App\PasswordSecurity');
      if(empty($ps)){
        $ps = [];
      }
      return $ps;
    }

    public function level() {
        $roles = explode(',',$this->roles);
        $foundVal = 0;
        foreach ($roles as $role){
            $splitRole = explode(':',$role);
            if(empty($splitRole[1])){
                continue;
            }
            $level = intval($splitRole[1]);
            if($foundVal<$level){
                $foundVal = $level;
            }
        }
        return $foundVal;
    }

    public function email(){
      $email = '';
      if(!empty(\Auth::id())){
      if(\Auth::user()->hasRole('admin')){
        $email = $this->email;
      }
      }
      return $email;
    }
    public function avatar(){
      if(empty($this->avatar)){
        return "img/404/avatar.png";
      }
      return $this->avatar;
    }

    public function background(){
      if(empty($this->background)){
        return "img/404/background.png";
      }
      return $this->background;
    }

    public function created_at_readable(){
      return $this->created_at->diffForHumans();
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
