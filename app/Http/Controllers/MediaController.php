<?php

namespace App\Http\Controllers;
use App\Media;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $data = Media::orderBy('id','DESC')->paginate(5);
      return view('medias.index',compact('data'))
          ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function directUploadView(Request $request)
    {
      return view('directupload');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function directUpload(Request $request){
      $file = $request->file('directMedia');
      $title = $request->input('title');
      //Display File Name
      echo 'File Name: '.$file->getClientOriginalName(). "   ".Auth::id();
      //Move Uploaded File
      $extension = $file->getClientOriginalExtension();

      if(($extension=="mp4")||($extension=="webm")){
        $path = $file->store('public/directMedia');
        $media = Media::create(['title' => $title,'source' => $path,'type' => 'localVideo', 'users_id' => Auth::id()]);
        //  $media->save();
        return view('directupload');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($title)
     {
         //$media = Media::find($id);
         $media = Media::where('title', '=' ,$title)->firstOrFail();
         return view('medias.show',compact('media'));
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
