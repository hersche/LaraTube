<?php
use App\License;
namespace App\Http\Controllers;
use App\Http\Resources\License as LicenseResource;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    //
    public function create(Request $request)
    {
      if(!empty(Auth::id())){
        $license = License::create(['title' =>  $request->input('title'),'short_description' => $request->input('short_description'),'description' => $request->input('description')]);
        return LicenseResource::collection(License::all());
      }
    }
    
    public function update(Request $request)
    {
      if(!empty(Auth::id())){
        $license = License::find($request->input("lid"));
        $license->update($request->all());
        //$license = License::create(['title' =>  $request->input('title'),'short_description' => $request->input('short_description'),'description' => $request->input('description')]);
        return LicenseResource::collection(License::all());
      }
    }
    
    public function remove(Request $request)
    {
      if(!empty(Auth::id())){
        $p = License::find($request->input('license_id'));
        $p->delete();
        return LicenseResource::collection(License::all());
      }
    }
}
