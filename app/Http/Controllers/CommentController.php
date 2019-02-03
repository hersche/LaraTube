<?php

namespace App\Http\Controllers;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Media;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function create(Request $request)
    {
        $comment = Comment::create(['media_id' =>  $request->input('medias_id'),'parent_id' =>  $request->input('parent_id'),'user_id' => Auth::id(),'body' => $request->input('body')]);
        return new CommentResource($comment);
      //  return redirect()->route('media.show',$request->input('medias_title'))
        //                ->with('success','Comment created successfully');
    }

    public function destroy(Request $request,$id)
    {
        //
        $comment = Comment::where('id', '=' ,$id)->firstOrFail();
        $mid = $comment->media_id;
        if((Auth::id()==$comment->user_id)||(Auth::user()->can('admin'))){
          foreach($comment->childs() as $c){
            $c->delete();
          }
          $comment->delete();
          
          return response()->json(["data"=>["media_id" => $mid,"msg"=>"Commented deleted"]],200);
        }
        return response()->json(["data"=>["media_id" => $mid,"msg"=>"Commented delete-fail"]],403);
    }
}
