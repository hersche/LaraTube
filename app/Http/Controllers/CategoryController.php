<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function create(Request $request)
    {
        $media = Category::create(['title' =>  $request->input('title'),'description' => $request->input('description'),'parent_id' => $request->input('parent_id')]);
        return;
      //  return redirect()->route('media.show',$request->input('medias_title'))
        //                ->with('success','Comment created successfully');
    }

    public function destroy(Request $request)
    {
        //
        $comment = Category::where('id', '=' ,$request->input('category_id'))->firstOrFail();
        if(Auth::id()==$comment->users_id){
          $comment->delete();
          return;
          //return redirect()->route('media')
            //            ->with('success','Comment deleted successfully');
        }
    }

}
