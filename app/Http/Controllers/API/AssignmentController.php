<?php

namespace App\Http\Controllers\API;
use App\Assignment;
use App\Has_arm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Level;
use App\Student;
use App\Studentassignment;
use Illuminate\Support\Facades\Auth;
use ZanySoft\Zip\ZipManager;
use ZanySoft\Zip\ZipFacade as Zip;
class AssignmentController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $user =auth('api')->user();
        if($user->type==='student'){
            $student=Student::findOrFail($user->student_id);
            return Assignment::with(['subject','level','arms'])->where([['school_id',auth('api')->user()->school_id],['level_id',$student->class_id],['arm_id',$student->arm_id]])->latest()->paginate(10);
        }

        if($user->type==='parent'){
            $levelIds=Student::whereIn('parent_id',[$user->parent_id])->pluck('class_id');
            $armIds=Student::whereIn('parent_id',[$user->parent_id])->pluck('arm_id');
            return Assignment::with(['subject','level','arms'])
             ->where('school_id',auth('api')->user()->school_id)
             ->whereIn('level_id',$levelIds)
             ->whereIn('arm_id',$armIds)
             ->latest()->paginate(20);
        }

        if( $user->type==='tutor'){
             $emp=Has_arm::where('staff_id',$user->staff_id)->first();
             return Assignment::with(['subject','level','arms'])->where([['school_id',auth('api')->user()->school_id],['level_id',$emp->level_id],['arm_id',$emp->arms_id]])->latest()->paginate(10);

            }
        return Assignment::with(['subject','level','arms'])->where('school_id',auth('api')->user()->school_id)->latest()->paginate(10);
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

        //return $request->note;
        $this->validate($request,[
            'level_id' => 'required',
            'subject_id' => 'required|string|max:191',
            'comment' => 'sometimes|required|max:191',
        ]);
      $path='';
        $is_pdf=0;
        if($request->hasFile('file')){
            $is_pdf=1;
            $path=Storage::disk('public_uploads')->put('assignment', $request->file('file'));
    }

    $assignment=new Assignment();

    $assignment->subject_id=$request->subject_id;

    $assignment->comment=$request->comment;
    $assignment->level_id=$request->level_id;
    $assignment->arm_id=$request->arm_id;
    $assignment->note=$request->note;
    $assignment->staff_id=auth('api')->user()->id;


    $assignment->file_name=$path;
    $assignment->school_id=auth('api')->user()->school_id;
    $assignment->is_pdf=$is_pdf;
    $assignment->save();
    return $assignment;
}

 public function getPdf($id){
     return Assignment::findOrFail($id);
 }

    public function show($id)
    {
        //

        $headers = ['Content-Type: application/pdf'];
        $assignment=Assignment::findOrFail($id);
        $path=public_path('/'.$assignment->file_name);
        return response()->download($path,'assignment.pdf',$headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zipUpload(Request $request)
    {

           $this->validate($request,[
       'file' => 'max:1000000',
       'image_type'=>'required',
    //'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,

]);


       if($request->hasFile('file')){
   $manager = new ZipManager();
  $path=  Storage::putFile('zipfiles', $request->file('file'));
  //return $path ;
// register existing zips
 $manager->addZip(Zip::open(storage_path('/'.$path)) );
 if($request->image_type==='profile_pictures'){
 $extract = $manager->extract(public_path('img/profile/'), false);
 }else{
   $extract = $manager->extract(public_path('img/signatures/'), false);
 }
 return Storage::delete($path);



    }
    else{
        return ['error'=>'the zip file contains no image'];
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

        $this->validate($request,[
            'level_id' => 'required',
            'subject_id' => 'required|string|max:191',
            'comment' => 'sometimes|required|max:191',
            'file_name' => 'required|string|max:191',
        ]);

        if($request->hasFile('file')){

            $assignment=Assignment::findOrFail($id);

            $assignment->subject_id=$request->subject_id;

            $assignment->comment=$request->comment;
            $assignment->level_id=$request->level_id;
            $assignment->staff_id=auth('api')->user()->id;
            $path=Storage::disk('public_uploads')->put('assignment', $request->file('file'));
            $assignment->file_name=$path;
            $assignment->save();
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
        $assignment=Assignment::findOrFail($id);
        Storage::disk('public_uploads')->delete($assignment->file_name);
        $assignment->delete();
        return ['message'=>'Assignment deleted successfully'];
    }
    public function studentAssignment(Request $request){
        $this->validate($request,[
            'level_id' => 'required',


        ]);
        $path='';
        $is_pdf=0;
        if($request->hasFile('file')){
        $is_pdf=1;
            $path=Storage::disk('public_uploads')->put('assignment', $request->file('file'));
    }
             $student_id=auth('api')->user()->student_id;
    $assignment=new Studentassignment();
    $assignment->assignment_id=$request->id;
    $assignment->level_id=$request->level_id;
    $assignment->arm_id=$request->arm_id;
    $assignment->note=$request->note;
    $assignment->student_id=$student_id;
    $assignment->file_name=$path;
    $assignment->is_pdf=$is_pdf;
    $assignment->save();
    return $assignment;
    }

    public function checkAssignment($id){
        $student_id=auth('api')->user()->student_id;
        $assignment=Studentassignment::where([['assignment_id',$id],['student_id',$student_id]])
        ->first();
        if($assignment){
          return  ['is_submited'=>1,
                          'assignment'=>$assignment];
        }
        return  ['is_submited'=>0];

    }

    public function getStudentAssignment($id){
          return Studentassignment::with('students')->where('assignment_id',$id)
          ->paginate(20);
    }
    public function updateStudentAssignment(Request $request){
             $assignment=Studentassignment::findOrFail($request->id);
             $assignment->score=$request->score;
             $assignment->is_graded=1;
             $assignment->comment=$request->comment;
             $assignment->save();
        return $assignment;
  }


  public function studentPDF($id)
    {
        //

        $headers = ['Content-Type: application/pdf'];
        $assignment=Studentassignment::findOrFail($id);
        $path=public_path('/'.$assignment->file_name);
        return response()->download($path,'assignment.pdf',$headers);
    }
}
