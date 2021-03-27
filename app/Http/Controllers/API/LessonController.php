<?php

namespace App\Http\Controllers\API;
use App\Lesson_note;
use App\Has_arm;
use App\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use auth;
class LessonController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
    $user =auth('api')->user();
        if($user->type==='student'){
            $student=Student::findOrFail($user->student_id);
            return Lesson_note::with(['subject','level','arms'])->where([['school_id',auth('api')->user()->school_id],['level_id',$student->class_id],['arm_id',$student->arm_id]])->latest()->paginate(10);
        }

        if( $user->type==='tutor'){
             $emp=Has_arm::where('staff_id',$user->staff_id)->first();
             return Lesson_note::with(['subject','level','arms'])->where([['school_id',auth('api')->user()->school_id],['level_id',$emp->level_id],['arm_id',$emp->arms_id]])->latest()->paginate(10);

            }
        return Lesson_note::with(['subject','level','arms'])->where('school_id',auth('api')->user()->school_id)->latest()->paginate(10);
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
    {    //return $request->all();
        $this->validate($request,[
            'level_id' => 'required',
            'subject_id' => 'required|string|max:191',
            'topic' => 'sometimes|required|max:191',
        ]);
 //return $request->all();
        if($request->hasFile('file')){

            $lesson=new Lesson_note();

            $lesson->subject_id=$request->subject_id;

            $lesson->topic=$request->topic;
            $lesson->level_id=$request->level_id;
            $lesson->arm_id=$request->arm_id;
            $lesson->staff_id=auth('api')->user()->id;
            $path=Storage::disk('public_uploads')->put('lesson', $request->file('file'));
            $lesson->file_name=$path;
            $lesson->school_id=auth('api')->user()->school_id;
            $lesson->save();
    }
    return $lesson;
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

        $headers = ['Content-Type: application/pdf'];
        $leson=Lesson_note::findOrFail($id);
        $path=public_path('/'.$leson->file_name);
        return response()->download($path,'assignment.pdf',$headers);
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

        $this->validate($request,[
            'level_id' => 'required',
            'subject_id' => 'required|string|max:191',
            'topic' => 'sometimes|required|max:191',
            'file_name' => 'required|string|max:191',
        ]);

        if($request->hasFile('file')){

            $lesson=Lesson_note::findOrFail($id);

            $lesson->subject_id=$request->subject_id;

            $lesson->comment=$request->comment;
            $lesson->level_id=$request->level_id;
            $lesson->staff_id=auth('api')->user()->id;
            $path=Storage::disk('public_uploads')->put('lesson', $request->file('file'));
            $lesson->file_name=$path;
             $lesson->school_id=$request->school->id;
            $lesson->save();
    }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $lesson=Lesson_note::findOrFail($id);
        Storage::disk('public_uploads')->delete($lesson->file_name);
        $lesson->delete();
        return ['message'=>'Assignment deleted successfully'];
    }
}
