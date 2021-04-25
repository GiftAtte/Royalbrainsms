<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Teachersubject;
use App\Subject;
use App\Level_sub;

class TeachersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return Teachersubject::with(['levels','subjects'])->where('staff_id',auth('api')->user()->staff_id)->get();
    }

    public function store(Request $request)

    {   $user = auth('api')->user();


 //return $request->all();
         $subject=Teachersubject::where([['subject_id',$request->subject_id],
                                     ['level_id',$request->level_id],['staff_id',$user->id]])->get();
           // $has_arm=Has_arm::where('staff_id',$user->staff_id)->first();
          if( count($subject)===0){

              try{
            return Teachersubject::create([
                'level_id'=>$request->level_id,
                'subject_id'=>$request->subject_id,
                'staff_id'      =>$user->staff_id,
                'school_id'      =>$user->school_id,
            ]);
          }catch(Exception $e){
           return $e;
          }
        }
        return ['message'=>'subject exist'];
    }





    public function delete_list($id)
    {   Teachersubject::where('id',$id)->delete();
       // Level_sub::delete('id',$id);

    }


    public function loadSubjects($id=null)

    {
          if(!empty($id)){
              return Level_sub::with('subjects')->where('level_id',$id)->get();
          }
        return Subject::where('school_id',auth('api')->user()->school_id)->get();


    }
}
