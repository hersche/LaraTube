<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use Auth;

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

    public function edit(Request $request,$id)
    {
        $cat = Category::find($id);
        $cat->title=$request->input('title');
        $cat->description = $request->input('description');
        $cat->parent_id = $request->input('parent_id');
        $cat->save();
        //$media = Category::create(['title' =>  $request->input('title'),'description' => $request->input('description'),'parent_id' => $request->input('parent_id')]);
        return;
      //  return redirect()->route('media.show',$request->input('medias_title'))
        //                ->with('success','Comment created successfully');
    }

    public function destroy(Request $request,$id)
    {
        //
        $comment = Category::find($id);
        if(!empty(Auth::id())) {
          $comment->delete();
          return;
          //return redirect()->route('media')
            //            ->with('success','Comment deleted successfully');
        }
    }

}
