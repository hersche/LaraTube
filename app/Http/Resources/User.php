<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class User extends JsonResource
{
  private function getUserIds($users,$rid=true){
    $ids = array();
    foreach($users as $u){
      if($u->recipient_id!=Auth::id()){
        array_push($ids,$u->recipient_id);
      } else {
        array_push($ids,$u->sender_id);
      }
    }
    return $ids;
  }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $mediaIds = array();
      foreach($this->medias() as $media){
        array_push($mediaIds, $media->id);
      }

      $email = '';
      $google2fa_url = "";
      $admin = false;
      if(!empty(Auth::id())){
        if(Auth::user()->level()>(int)config('adminlevel')){
          $email = $this->email;
          $admin = true;
        }
        if($this->id==Auth::id()){
          $email = $this->email;
        }

      }
      $avatar = $this->avatar();
      if((substr( $avatar, 0, 4 ) === "http")==false){
        $avatar = env('MIX_APP_URL', "")."/".$avatar;
      }
      $background = $this->background();
      if((substr( $background, 0, 4 ) === "http")==false){
        $background = env('MIX_APP_URL', "")."/".$background;
      }
      $bio = $this->bio;
      if(is_null($bio)){
        $bio="";
      }
      $simpleRoleArray = [];
      $i=0;
      foreach(explode(',',$this->roles) as $role){
          $splitRoles = explode(':',$role);
          $simpleRoleArray[$i] = ["slug"=>$splitRoles[0],"level"=>$splitRoles[1]];
          $i++;
      }
      return [
          'id' => $this->id,
          'name' => $this->name,
          'username' => $this->username,
          'avatar' => $avatar,
          'background' => $background,
          'bio' => $bio,
          'tagString' => $this->tagString(),
          'mediaIds' => $mediaIds,
          'public' => $this->public,
          'admin' => $admin,
          'email' => $email,
          'friends' => [
            'pending' => $this->getUserIds($this->getPendingFriendships()),
            'accepted' => $this->getUserIds($this->getAcceptedFriendships()),
            'denied' => $this->getUserIds($this->getDeniedFriendships()),
            'blocked' => $this->getUserIds($this->getBlockedFriendships()),
            'pendingRequests' => $this->getUserIds($this->getFriendRequests(),false),
          ],
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
      ];
    }
}
