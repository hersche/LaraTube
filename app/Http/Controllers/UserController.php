<?php


namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Like;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller

{

  function __construct()
  {
  //     $this->middleware('permission:admin');
    //   $this->middleware('permission:user-admin');
  }



    public function info(){
      $au = Auth::id();

    //  if($au==False){
      //  return "{ login: 0}";
    //  }
      return "{ login: ".$au."}";
    }
    public function changeFriends(Request $request)
    {
      $input = $request->all();
      if($input['type']=="request"){
        $friend = User::find($input['users_id']);
        if(Auth::user()->hasFriendRequestFrom($friend)){
          Auth::user()->acceptFriendRequest($friend);
        } else {
          Auth::user()->befriend($friend);
        }

      }
      if($input['type']=="confirm"){
        $friend = User::find($input['users_id']);
        Auth::user()->acceptFriendRequest($friend);
      }
      if($input['type']=="unfriend"){
        $friend = User::find($input['users_id']);
        Auth::user()->unfriend($friend);
      }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $avatar_source = 'public/user/avatars/'.$user->name.'.png';
        $data = $request->input('avatar');
        if(!empty($data)){
          //echo $data;
          list($type, $data) = explode(';', $data);
          list(, $data)      = explode(',', $data);
          $data = base64_decode($data);
          Storage::put('public/user/avatars/'.$user->name.'.png', $data);
        } else {
          $avatar_source = '';
        }
        $background_source = 'public/user/backgrounds/'.$user->name.'.png';
        $data = $request->input('background');
        if(!empty($data)){
          list($type, $data) = explode(';', $data);
          list(, $data)      = explode(',', $data);
          $data = base64_decode($data);
          Storage::put('public/user/backgrounds/'.$user->name.'.png', $data);
        } else {
          $background_source = '';
        }
        $user->avatar_source = $avatar_source;
        $user->background_source = $background_source;
        if(!empty($request->input('roles'))){
          $user->assignRole($request->input('roles'));
        }
        $tagArrayExtract = explode(' ', $request->input('tags'));
        $tagArray = array();
        foreach($tagArrayExtract as $tag){
          if(starts_with($tag, '#')){
            array_push($tagArray, substr($tag,1));
          } else {
            array_push($tagArray, $tag);
          }
        }
        $user->retag($tagArray);
        return response()->json(["data"=>["msg"=>"User created"]],200);
      //  return redirect()->route('users.index')
      //                  ->with('success','User created successfully');
    }

    public function mkAdmin(Request $request, $id){
      //if(\Auth::user()->can('admin')){
        $user = User::find($id);
        $user->syncPermissions(['admin']);
      //}
      return response()->json(["data"=>["msg"=>"mkAdmin!"]],200);
    }

    public function rmAdmin(Request $request, $id){
      //if(\Auth::user()->can('admin')){
        $user = User::find($id);
        $user->syncPermissions([]);
      //}
      return response()->json(["data"=>["msg"=>"rmAdmin!"]],200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
          //  'password' => 'same:confirm-password',
            //'roles' => 'required'
        ]);
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }
        $user = User::find($id);
        $user->update($input);
        $avatar_source = 'public/user/avatars/'.$user->name.'.png';
        $data = $request->input('avatar');
        if(!empty($data)){
          //echo $data;
          list($type, $data) = explode(';', $data);
          list(, $data)      = explode(',', $data);
          $data = base64_decode($data);
          Storage::put('public/user/avatars/'.$user->name.'.png', $data);
        } else {
          $avatar_source = '';
        }
        $background_source = 'public/user/backgrounds/'.$user->name.'.png';
        $data = $request->input('background');
        if(!empty($data)){
          list($type, $data) = explode(';', $data);
          list(, $data)      = explode(',', $data);
          $data = base64_decode($data);
          Storage::put('public/user/backgrounds/'.$user->name.'.png', $data);
        } else {
          $background_source = '';
        }
        $user->avatar_source = $avatar_source;
        $user->background_source = $background_source;

        $user->save();
        if(!empty($request->input('roles'))){
          DB::table('model_has_roles')->where('model_id',$id)->delete();
          $user->assignRole($request->input('roles'));
        }
        $tagArrayExtract = explode(' ', $request->input('tags'));
        $tagArray = array();
        foreach($tagArrayExtract as $tag){
          if(starts_with($tag, '#')){
            array_push($tagArray, substr($tag,1));
          } else {
            array_push($tagArray, $tag);
          }
        }
        $user->retag($tagArray);
        return response()->json(["data"=>["msg"=>"User updated"]],200);
      //  return redirect()->route('profile.view', $user->name)
        //                ->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(["data"=>["msg"=>"User deleted"]],200);
    }
}
