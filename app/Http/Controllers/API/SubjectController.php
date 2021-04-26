<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subject;
use App\Level;
use App\Level_sub;
use App\Has_arm;
use App\Student;
use App\Report;
use App\Teachersubject;
use Dotenv\Result\Success;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user=auth('api')->user();
        if($user->type==='student'){
        $student=Student::findOrFail($user->student_id);
        $subject=Level_sub::where('level_id',$student->class_id)->pluck('subject_id');
        return Subject::whereIn('id',$subject)->latest()->paginate(10);

    }
        return Subject::where('school_id',$user->school_id)->latest()->paginate(10);


    }

  public function adminSubject(){

  }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191',
        ]);
        return Subject::create([
            'name'=>$request->name,
            'code'=>$request->code,
            'school_id'=>auth('api')->user()->school_id
        ]);

    }
  // update
    public function update(Request $request, $id)
    {
    $subject= Subject::findOrFail($id);
    $this->validate($request,[
        'name' => 'required|string|max:191',
    ]);
    $subject->save($request->all());
        //
    }


    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $user = Subject::findOrFail($id);
        // delete the user

        $user->delete();

        return ['message' => 'subject Deleted'];
    }

     public function loadSubjects($id=null,$is_scores=false)

    {      // $permitted=false;
        $report=Report::find($id);
        $user=auth('api')->user();
            if(($is_scores>0) && ($user->type==='subjectTeacher')){

                    $subjects=Teachersubject::where([['level_id',$report->level_id],['staff_id',$user->staff_id]])->pluck('subject_id');
                      if($subjects){
                        return Level_sub::with('subjects')->where('level_id',$report->level_id)->whereIn('subject_id',$subjects)->get();
                      }
             else{
                 return '';
             }

                }



        if(!empty($id)){

            if($report){
                return Level_sub::with('subjects')->where('level_id',$report->level_id)->get();
            }else{
                return Level_sub::with('subjects')->where('level_id',$id)->get();
            }

          }
        return Subject::where('school_id',auth('api')->user()->school_id)->get();


    }
    public function level_subjects(Request $request)

    {   $user = auth('api')->user();
         $level_id=null;
         if($user->type==='admin'||$user->type==='superadmin'){
             $level_id=$request->level_id;
         }
         else{
            $level=Level::where('staff_id',$user->staff_id)->first();
             $level_id=$level->id;
         }

         $subject=Level_sub::where([['subject_id',$request->subject_id],
                                     ['level_id',$level_id]])->get();
           // $has_arm=Has_arm::where('staff_id',$user->staff_id)->first();
          if( count($subject)===0){


            return Level_sub::create([
                'level_id'=>$level_id,
                'subject_id'=>$request->subject_id,
                'type'      =>$request->type
            ]);



        }
        return ['message'=>'subject exist'];
    }

    public function delete_list($id)
    {   $ls=Level_sub::where('id',$id)->delete();
       // Level_sub::delete('id',$id);

    }

    public function loadListView($id=null)
    {
        if(!empty($id)){
            return Level_sub::with('subjects')->where('level_id',$id)->get();
        }
       return Subject::where('school_id',auth('api')->user()->school_id);
    }
}
