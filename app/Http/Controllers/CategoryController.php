<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;
use Auth;

class CategoryController extends Controller
{
    //
    public function create(Request $request)
    {
        $cat = Category::create(['title' =>  $request->input('title'),'description' => $request->input('description'),'parent_id' => $request->input('parent_id')]);
        return new CategoryResource($cat);
    }

    public function edit(Request $request,$id)
    {
        $cat = Category::find($id);
        $cat->title=$request->input('title');
        $cat->description = $request->input('description');
        $cat->parent_id = $request->input('parent_id');
        $cat->save();
        return new CategoryResource($cat);
    }

    public function destroy(Request $request,$id)
    {
        //
        $comment = Category::find($id);
        if(!empty(Auth::id())) {
          $comment->delete();
          return response()->json(["data"=>["msg"=>"Category deleted"]],200);
        }
    }

}
