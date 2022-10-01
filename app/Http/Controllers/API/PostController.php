<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Post::with('users')
                     ->where('school_id',AppUtils::getSchoolId())
                     ->latest()
                     ->paginate(10);
    }



 public function publishedPost()
    {
       return Post::with('users')
                     ->where('school_id',AppUtils::getSchoolId())
                     ->where('isPublished',1)
                     ->latest()
                     ->get();
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publishPost($id){
               $post=  Post::findOrFail($id);
                 $isPublished=$post->isPublished;
                Post::where('id',$id)->update(['isPublished'=>!$isPublished]);
                return [
                    'message'=>'updated Successfully'
                ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        $user=auth('api')->user();
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
        ]);
        $postId=  Post::create([
              'title'=>$request->title,
              'content'=>$request->content,
              'school_id'=>AppUtils::getSchoolId(),
              'user_id'=>Auth::id(),
              'isPublished'=>0
          ])->id;

       if($request->has('post_img')){
       $file=$request->file('post_img');
          $img_name=$postId.'.png';
         $file->move(public_path('img/post'),$img_name);
       }

        return [
            'code'=>201,
            'message'=>'success'
        ];
    }




    public function update(Request $request)
    {
             $user=auth('api')->user();
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
        ]);
          Post::where('id',$request->id)
             ->update([
              'title'=>$request->title,
              'content'=>$request->content,
              'school_id'=>$user->school_id,
              'user_id'=>Auth::id()
          ]);

       $img_name=$request->id.'.png';

   if($request->has('post_img') && $request->post_img!=$img_name  ){
       $file=$request->file('post_img');


            if($file){
                 $userPhoto = public_path('img/post/').$img_name;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
            $file->move(public_path('img/post'),$img_name);
            }



        }






    }


    public function destroy($id)
    {
        Post::destroy($id);
        return "Deleted Successfully";
    }
}
