<?php


namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
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
        $user->retag(explode(' ', $request->input('tags')));
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

    public function updateAvatar(Request $request)
    {
      $data = $request->input('image');
      list($type, $data) = explode(';', $data);
      list(, $data)      = explode(',', $data);
      $data = base64_decode($data);
    //  $imageName = time().'.png';
    //  file_put_contents('upload/'.$imageName, $data);
    //  echo 'done';
      $user = User::find(Auth::id());
      if(!empty($user->avatar_source)){
        Storage::delete($user->avatar_source);
      }
        $user->avatar_source = 'public/u/avatars/'.$user->id.'.png';
        Storage::put('public/u/avatars/'.$user->id.'.png', $data);
        $user->save();
        return;
    }


    public function updateBackground(Request $request)
    {
      $user = User::find(Auth::id());
      if(!empty($user->background_source)){
        Storage::delete($user->background_source);
      }
      $file = $request->file('background_source');
      $extension = $file->getClientOriginalExtension();
      if(($extension=="jpg")||($extension=="jpeg")||($extension=="png")||($extension=="gif")){
        $user->background_source = $file->store('public/u/backgrounds');
        $user->save();
        return redirect()->route('users.selfedit')
                        ->with('success','Background uploaded with success');
      }
      return redirect()->route('users.selfedit')
                      ->with('error','No background updated');
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
            'email' => 'required|email|unique:users,email,'.$id,
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
        if(!empty($request->input('roles'))){
          DB::table('model_has_roles')->where('model_id',$id)->delete();
          $user->assignRole($request->input('roles'));
        }
        $user->retag(explode(' ', $request->input('tags')));
        return redirect()->route('users.index')
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
