<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function create(Request $request)
    {
        $media = Comment::create(['medias_id' =>  $request->input('medias_id'),'users_id' => Auth::id(),'body' => $request->input('body')]);
        return redirect()->route('medias.add')
                        ->with('success','Video created successfully');
    }
}
