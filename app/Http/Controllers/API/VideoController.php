<?php

namespace App\Http\Controllers\API;
use App\Video;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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
    //return $request->all();
 $this->validate($request,[
    'level_id' => 'required',
    'title' => 'sometimes|required|max:191',

]);

$video=new Video();
if($request->hasFile('file')){
    $user=auth('api')->user();


    $video->school_id=$user->school_id;
    $video->level_id=$request->level_id;
    $video->title=$request->title;
    $video->author_id=$user->id;
    // if(!Storage::disk('public_uploads')->put($path, $file_content)) {
    //     return false;
    // }
    $path=  Storage::disk('public_uploads')->put('videos', $request->file('file'));
   // $path=Storage::putFile('videos', $request->file('file'));
    $video->video_path=explode('/',$path)[1];

    $video->save();
}
return $video;
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
    public function edit($id)
    {
        //
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


