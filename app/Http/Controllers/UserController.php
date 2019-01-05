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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function profile()
    {
        $users = User::all();
        $friends = Auth::user()->getAcceptedFriendships();
        return view('profile.index',compact('users','friends'));
    }

    public function info(){
      $au = Auth::id();

    //  if($au==False){
      //  return "{ login: 0}";
    //  }
      return "{ login: ".$au."}";
    }

    public function profileview($name)
    {
        $user = User::where('name','=',$name)->firstOrFail();
      //  echo $user->name; exit;
      $medias = $user->medias;
        return view('profile.view',compact('user','medias'));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
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
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
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
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole'));
    }

    public function selfEdit(){
      $user = User::find(Auth::user()->id);
      return view('users.selfedit',compact('user'));
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
            'password' => 'same:confirm-password',
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
        return redirect()->route('profile.view', $user->name)
                        ->with('success','User updated successfully');
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
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}
