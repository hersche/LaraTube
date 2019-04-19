<?php

namespace App\Http\Controllers;
use App\Media;
use App\MediaSource;
use App\MediaChapter;
use App\MediaView;
use App\User;
use App\Notifications\LikeReceived;
use App\Http\Resources\Media as MediaResource;
use App\Comment;
use App\Like;
use App\Track;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{


    public function addTrack(Request $request){
      if(!empty(Auth::id())){
      $media_id = $request->input("media_id");
      $lang = $request->input("lang");
      $file = $request->file('track');
      $source = $file->store('public/tracks');
      $extension = $file->getClientOriginalExtension();
      Track::create(["lang"=>$lang,"source"=>$source,"media_id"=>$media_id,"type"=>$extension]);
      $media = Media::find($media_id);
      return new MediaResource($media);
    }
    }

    public function deleteTrack(Request $request,$trackid){
      if(!empty(Auth::id())){
        $media = Media::find(Track::find($trackid)->media_id);
        Track::find($trackid)->delete();
        return new MediaResource($media);
      }
    }
    private function getMediaOrder($sortByInput){
      $ascDesc = 'desc';
      $sortBy = 'updated_at';
      //$sortByInput = $request->input('sortBy');
      if($sortByInput=="title"){
        $ascDesc = 'asc';
        $sortBy = 'title';
      } else if($sortByInput=="title_reverse"){
        $ascDesc = 'desc';
        $sortBy = 'title';
      }else if($sortByInput=="created_at"){
        $ascDesc = 'asc';
        $sortBy = 'created_at';
      }else if($sortByInput=="created_at_reverse"){
        $ascDesc = 'desc';
        $sortBy = 'created_at';
      }else if($sortByInput=="updated_at"){
        $ascDesc = 'asc';
        $sortBy = 'updated_at';
      }else if($sortByInput=="updated_at_reverse"){
        $ascDesc = 'desc';
        $sortBy = 'updated_at';
      }else if($sortByInput=="type"){
        $ascDesc = 'asc';
        $sortBy = 'type';
      }else if($sortByInput=="type_reverse"){
        $ascDesc = 'desc';
        $sortBy = 'type';
      }
      return [$ascDesc,$sortBy];
    }
    public function getById(Request $request,$id){
      return new MediaResource(Media::where('id', '=' ,$id)->firstOrFail());
    }
    public function getByTitle(Request $request,$title){
      $title = urldecode($title);
      return new MediaResource(Media::where('title', '=' ,$title)->firstOrFail());
    }
    public function getAll(Request $request){
      $sortBy = getMediaOrder($request->input('sortBy'));
      return MediaResource::collection(Media::orderBy($sortBy[1], $sortBy[0])->whereNotIn('id', explode(",",$request->input('i')))->get());
    }
    
    public function get(Request $request){
      $types = explode(",",$request->input('types'));
      $tArr = [];
      foreach($types as $type){
        if($type=="audio"){
          $tArr = array_merge($tArr,['localAudio','torrentAudio','directAudio']);
        }
        if($type=="video"){
          $tArr = array_merge($tArr,['localVideo','torrentVideo','directVideo','youtube','vimeo']);
        }
      }
      // wheretIn('type', )
      $sortBy = getMediaOrder($request->input('sortBy'));
      $res = Media::orderBy($sortBy[1], $sortBy[0])->whereIn('base_type', $types)->whereNotIn('id', explode(",",$request->input('i')))->limit(3)->get();

      if(empty($res->count())){
        // Ignore the types, allows faster change. Still secondary
        $res = Media::orderBy($sortBy[1], $sortBy[0])->whereNotIn('id', explode(",",$request->input('i')))->limit(3)->get();
      }
    return MediaResource::collection($res);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      if(!empty(Auth::id())){
        $getID3 = new \getID3;
        $title = $request->input('title');
        $source = $request->input('source');
        $duration = 0;
        if(!empty($request->input('duration'))){
          $duration = $request->input('duration');
        }
        if(empty($source)){
          $file = $request->file('directMedia');
          //
          $source = $file->store('public/directMedia');
          $id3 = $getID3->analyze($source);
          if(!empty($id3)&&!empty($id3['playtime_string'])){
            $duration = $this->formatedDuration($id3['playtime_string']);
          }
        }
        $tagArrayExtract = array();
        if(!empty($request->input('tags'))){
          $tagArrayExtract = explode(' ', $request->input('tags'));
        }

        $tagArray = array();
        foreach($tagArrayExtract as $tag){
          if(starts_with($tag, '#')){
            array_push($tagArray, substr($tag,1));
          } else {
            array_push($tagArray, $tag);
          }
        }
        $media = Media::create(['title' =>  $request->input('title'), 'description' => $request->input('description'), 'user_id' => Auth::id(),'category_id' =>  $request->input('category_id'),]);
        MediaSource::create(['media_id'=>$media->id,'source' => $source,'poster_source' => '','duration' => $duration, 'type' => $request->input('type')]);
        $media->poster_source = $this->processPoster($media->id,$request->input('poster'));
        $media->save();
        $media->retag($tagArray);
        return new MediaResource($media);
      }
    }

    private function formatedDuration($duration){
            // The base case is A:BB
            if(strlen($duration) == 4){
                return "00:0" . $duration;
            }
            // If AA:BB
            else if(strlen($duration) == 5){
                return "00:" . $duration;
            }   // If A:BB:CC
            else if(strlen($duration) == 7){
                return "0" . $duration;
            }
        }
    private function processPoster($id, $data){
      if(!empty($data)){
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        Storage::put('public/media/posters/'.$id.'.png', $data);
        return 'public/media/posters/'.$id.'.png';
      } else {
        return '';
      }
    }
/*
    public function tags(Request $request){
      $tags = Media::existingTags();
      return view('tags.index',compact('tags'));
    }
*/


    public function like(Request $request){
      if(!empty(Auth::id())){
      $notifyId = 0;
      if(!empty($request->input('media_id'))){
        $like = Like::firstOrCreate(['user_id' => Auth::id(),'media_id' => $request->input('media_id')]);
        $notifyId = Media::find($request->input('media_id'))->user_id;
      } else if(!empty($request->input('comment_id'))){
        $like = Like::firstOrCreate(['user_id' => Auth::id(),'comment_id' => $request->input('comment_id')]);
        $notifyId = Comment::find($request->input('comment_id'))->user_id;
      }
        $like->count = $request->input('count');
        $like->save();
        User::find($notifyId)->notify(new LikeReceived($like));
        return response()->json(["data"=>["msg"=>"OK"]],200);
      } else {
        return response()->json(["data"=>["msg"=>"Permission denied"]],403);
      }
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
      $media = Media::where('id', '=' ,$id)->firstOrFail();
      if((Auth::id()==$media->user_id)||(Auth::user()->can('admin'))){
        $media->title = $request->input('title');
        $category_id = $request->input('category_id');
        if(empty($category_id)){
          $category_id = 0;
        }
        $media->category_id = $category_id;
        $duration = 0;
        if(!empty($request->input('duration'))){
          $duration = $request->input('duration');
        }
        $media->duration = $duration;
        //$media->source = $request->input('source');
        $media->description = $request->input('description');
        $tagArrayExtract = explode(' ', $request->input('tags'));
        $tagArray = array();
        foreach($tagArrayExtract as $tag){
          if(starts_with($tag, '#')){
            array_push($tagArray, substr($tag,1));
          } else {
            array_push($tagArray, $tag);
          }
        }
        $media->retag($tagArray);
        if(!empty($request->input('type'))){
          $media->type = $request->input('type');
        }
        $media->poster_source = $this->processPoster($media->id,$request->input('poster'));
        $media->save();
        return new MediaResource($media);
      }
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
        
        $media = Media::where('id', '=' ,$id)->firstOrFail();
        if((Auth::id()==$media->user_id)||(Auth::user()->hasRole('admin'))){
        $extension = pathinfo($media->source);
        if(!empty($extension['extension'])){
          $extension = $extension['extension'];
          if(($extension=="mp4")||($extension=="webm")||($extension=="mp3")||($extension=="ogg")){
            Storage::delete($media->source);
          }
        }
        //$likeList = $media->likeObjects();
        //foreach ($likeList as $value){


          $media->user()->notifications()->where('type', 'App\Notifications\LikeReceived')->where('data',"LIKE", '%"media_id":"'.$media->id.'"%')->delete();
        //}
        foreach ($media->comments() as $value){
          $media->user()->notifications()->where('type', 'App\Notifications\LikeReceived')->where('data',"LIKE", '%"comment_id":"'.$value->id.'"%')->delete();
          $value->delete();
        }
        Storage::delete($media->poster_source);
        $media->delete();
      }
        return response()->json(["data"=>["msg"=>"Media deleted"]],200);
    }
}
