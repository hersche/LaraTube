<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    function create(Request $request){
  //    echo "fooo";
    //  die();
      if(Auth::user()->level()>(int)config('adminlevel')){
        //Project::create($request->except(['_token']));
        config('roles.models.role')::create($request->except(['_token']));
        return $this->get($request);
      } else {
        echo "noadmin-fail";
        die();
      }
    }

    function get(Request $request){
      if(!empty(Auth::id())){
        if(Auth::user()->level()>(int)config('adminlevel')){
          return response()->json(config('roles.models.role')::all(),200);
        } else {
          return response()->json([],401);
        }
      }
    }
    
    function update(Request $request){
      if(Auth::user()->level()>(int)config('adminlevel')){
        $p = config('roles.models.role')::find($request->input('rid'));
        $p->update($request->except(['_token','rid']));
        return $this->get($request);
      }
    }
    
    function destroy(Request $request){
      if(Auth::user()->level()>(int)config('adminlevel')){
        config('roles.models.role')::find($request->input("rid"))->delete();
        return $this->get($request);
      }
    }
}
