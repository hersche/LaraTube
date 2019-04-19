<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
use Auth;

class FriendController extends Controller
{
    //
    public function get(Request $request){
      return UserResource::collection(User::all());
    }
    
    public function friendRequest(Request $request){
      $u = User::find(Auth::id());
      if(Auth::user()->level()>0){
        $fu = User::find($request->input('fid'));
        $u->befriend($fu);
        return UserResource::collection(User::all());
      }
    }
    public function acceptRequest(Request $request){
      $u = User::find(Auth::id());
      $fu = User::find($request->input('fid'));
      $u->acceptFriendRequest($fu);
      return UserResource::collection(User::all());
    }
    public function denyRequest(Request $request){
      $u = User::find(Auth::id());
      $fu = User::find($request->input('fid'));
      $u->denyFriendRequest($fu);
      return UserResource::collection(User::all());
    }
    public function unfriend(Request $request){
      $u = User::find(Auth::id());
      $fu = User::find($request->input('fid'));
      $u->unfriend($fu);
      return UserResource::collection(User::all());
    }
    public function block(Request $request){
      $u = User::find(Auth::id());
      $fu = User::find($request->input('fid'));
      $u->blockFriend($fu);
      return UserResource::collection(User::all());
    }
    public function unblock(Request $request){
      $u = User::find(Auth::id());
      $fu = User::find($request->input('fid'));
      $u->unblockFriend($fu);
      return UserResource::collection(User::all());
    }
}
