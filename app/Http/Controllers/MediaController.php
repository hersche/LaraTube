<?php

namespace App\Http\Controllers;
use App\Media;
use App\Comment;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


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




    public function addMedia(Request $request)
    {
      return view('addmedia');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $data = $request->input('image');
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $title = $request->input('title');
        Storage::put('public/media/posters/'.$title.'.png', $data);
        $media = Media::create(['title' =>  $request->input('title'),'source' => $request->input('source'),'poster_source' => 'public/media/posters/'.$title.'.png', 'description' => $request->input('description'), 'type' => $request->input('type'), 'users_id' => Auth::id()]);
        $media->retag(explode(' ', $request->input('tags')));
        return redirect()->route('media.show',$title)
                        ->with('success','Video created successfully');
    }

    public function directUpload(Request $request){
      $file = $request->file('directMedia');
    //  $posterFile = $request->file('poster');

      $data = $request->input('image');
      list($type, $data) = explode(';', $data);
      list(, $data)      = explode(',', $data);
      $data = base64_decode($data);
      $title = $request->input('title');
      //Move Uploaded File
      $file = $request->file("directMedia");
      $extension = $file->getClientOriginalExtension();

      if(($extension=="mp4")||($extension=="webm")){
        $path = $file->store('public/directMedia');
        //$posterFile = $request->file('poster');
        $posterPath = '';
        if(!empty($posterFile)){
          $posterPath = $posterFile->store('public/media/posters');
        }
        $media = Media::create(['title' => $title,'source' => $path,'poster_source' => 'public/media/posters/'.$title.'.png','type' => 'localVideo', 'description' => $request->input('description'), 'users_id' => Auth::id()]);
        $media->retag(explode(' ', $request->input('tags')));
        Storage::put('public/media/posters/'.$title.'.png', $data);
        return redirect()->route('media.show',$title)
                        ->with('success','Video created successfully');
      }
      else if(($extension=="mp3")||($extension=="ogg")){
        $path = $file->store('public/directMedia');
        $posterPath = $posterFile->store('public/media/posters');
        $media = Media::create(['title' => $title,'source' => $path,'poster_source' => 'public/media/posters/'.$title.'.png','type' => 'localAudio', 'description' => $request->input('description'), 'users_id' => Auth::id()]);
        $media->retag(explode(' ', $request->input('tags')));
        Storage::put('public/media/posters/'.$title.'.png', $data);
        return redirect()->route('media.show',$title)
                        ->with('success','Audio created successfully');
      } else {
        return redirect()->route('medias.add')
                        ->with('error','Media-format is wrong');
      }
      return view('addmedia');
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
    public function edit(Request $request, $title)
    {
        //
        $data = $request->input('image');
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $media = Media::where('title', '=' ,$title)->firstOrFail();
        $media->title = $request->input('title');
        $media->source = $request->input('source');
        $media->description = $request->input('description');
        $media->retag(explode(' ', $request->input('tags')));
        if(!empty($request->input('type'))){
          $media->type = $request->input('type');
        }

        if(!empty($media->poster_source)){
          Storage::delete($media->poster_source);
        }
        $media->poster_source = 'public/media/posters/'.$media->title.'.png';
        Storage::put('public/media/posters/'.$media->title.'.png', $data);
        $media->save();
        return redirect()->route('media.show',$media->title);
    }
    public function editView($title)
    {
        $media = Media::where('title', '=' ,$title)->firstOrFail();
        return view('medias.edit',compact('media'));
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
    public function destroy($title)
    {
        //
        $media = Media::where('title', '=' ,$title)->firstOrFail();
        $extension = pathinfo($media->source);
        if(!empty($extension['extension'])){
          $extension = $extension['extension'];
          if(($extension=="mp4")||($extension=="webm")||($extension=="mp3")||($extension=="ogg")){
            Storage::delete($media->source);
          }
        }
        Storage::delete($media->poster_source);
        $media->delete();
        return redirect()->route('media')
                        ->with('success','Media deleted successfully');
    }
}
