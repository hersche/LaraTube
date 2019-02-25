<?php

namespace App\Http\Controllers;
use App\Playlist;
use App\PlaylistMedia;
use App\Http\Resources\Playlist as PlaylistResource;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    //
    public function create(Request $request)
    {
      if(!empty(Auth::id())){
        $cat = Playlist::create(['title' =>  $request->input('title'),'description' => $request->input('description'),'user_id' => Auth::id()]);
        return new PlaylistResource($cat);
      }
    }
    
    public function remove(Request $request)
    {
      if(!empty(Auth::id())){
        $p = Playlist::find($request->input('playlist_id'));
        $p->delete();
        // TODO delete all medias too
      }
    }
    
    public function addMedia(Request $request)
    {
      if(!empty(Auth::id())){
        $p = Playlist::find($request->input('playlist_id'));
        if($p->user_id==Auth::id()){
        $cat = PlaylistMedia::create(['media_id' =>  $request->input('media_id'),'playlist_id' => $request->input('playlist_id')]);
        return new PlaylistResource($p);
      }
      }
    }
    
    public function removeMedia(Request $request)
    {
      if(!empty(Auth::id())){
        $p = Playlist::find($request->input('playlist_id'));
        if($p->user_id==Auth::id()){
        $cat = PlaylistMedia::find(['media_id' =>  $request->input('media_id')]);
        $cat->delete();
        return new PlaylistResource($p);
      }
      }
    }
}
