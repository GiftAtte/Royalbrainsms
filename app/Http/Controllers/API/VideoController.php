<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Video;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
ini_set('max_execution_time', '1000');
class VideoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Video::with(['levels','author'])->where('school_id',auth('api')
        ->user()->school_id)->latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  //  return $request->all();
 $this->validate($request,[
    'level_id' => 'required',
    'title' => 'sometimes|required|max:191',

]);

    $data=[];
    $user=auth('api')->user();
    $path='';
    if($request->type==='youtube_video'){
    $data['video_path']=$request->video_path;
    } else{
 if($request->has('file')){
    // if(!Storage::disk('public_uploads')->put($path, $file_content)) {
    //     return false;
    // }
   $path=  Storage::disk('public_uploads')->put('videos', $request->file('file'));
   //$path=Storage::putFile('videos', $request->file('file'));
   $data['video_path']=explode('/',$path)[1];
    }

    }



    $data['school_id']=AppUtils::getSchoolId();
    $data['level_id']=$request->level_id;
    $data['title']=$request->title;
    $data['author_id']=$user->id;
    $data['type']=$request->type;
   $id=$request->id;


return Video::updateOrCreate(['id'=>$id],$data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $headers = ['Content-Type: application/mp4'];
        $video=Video::findOrFail($id);
        $path=public_path('videos/'.$video->video_path);
        return response()->download($path,'lesson.mp4',$headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function play($id)
    { if($id){
      return Video::findOrFail($id);
    }

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
    public function destroy($id)
    {
        $video=Video::findOrFail($id);
        Storage::disk('public_uploads')->delete('/'.$video->video_path);
        $video->delete();

    }
}


