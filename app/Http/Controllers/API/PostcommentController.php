<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Post;
use App\Postcomment;
use Illuminate\Http\Request;

class PostcommentController extends Controller{


    public function index(Post $post)
{
   return response()->json(
$post->comments()->with('users')->where('school_id',auth('api')->user()->school_id)
->latest()->paginate(10)
   );
}


public function store(Request $request, Post $post)
    {        $user=auth('api')->user();
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
        ]);
        $comment=  $post->with('comments')->create([
              'title'=>$request->title,
              'content'=>$request->content,
              'school_id'=>$user->school_id,
              'user'=>$user->id
          ]);

          $comment=Postcomment::where('id',$comment->id)->with('users')->first();
      return $comment->toJson();
        }


}
