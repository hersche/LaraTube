<?php

namespace App\Http\Controllers;
use App\Comment;
use App\Media;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function create(Request $request)
    {
        $media = Comment::create(['medias_id' =>  $request->input('medias_id'),'users_id' => Auth::id(),'body' => $request->input('body')]);
        return redirect()->route('media.show',$request->input('medias_title'))
                        ->with('success','Comment created successfully');
    }

    public function destroy($id)
    {
        //
        $comment = Comment::where('id', '=' ,$id)->firstOrFail();
        if(Auth::id()==$comment->users_id){
          $comment->delete();
          return redirect()->route('media')
                        ->with('success','Comment deleted successfully');
        }
    }
}
