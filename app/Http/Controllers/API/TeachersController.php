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


    public function index($id=null)
    {
        if($id){
            return Teachersubject::with(['levels','subjects'])->where('staff_id',$id)->get();
        }else{
        return Teachersubject::with(['levels','subjects'])->where('staff_id',auth('api')->user()->staff_id)->get();
    }}

    public function store(Request $request)

    {   $user = auth('api')->user();
        $staff_id=$request->staff_id;
        if(empty($staff_id)){
          if($user->type==='superadmin'){
            $staff_id=$user->staff_id||$user->staff_id;
        
             }
            }


       $subjectIDs=$request->subjects;
       foreach($subjectIDs as $subject_id){

         $check=Teachersubject::where([['subject_id',$subject_id],
                                     ['level_id',$request->level_id],['staff_id',$user->id]])->get();
  
           if( count($check)===0){
    
                Teachersubject::create([
                'level_id'=>$request->level_id,
                'subject_id'=>$subject_id,
                'staff_id'      =>$staff_id,
                'school_id'      =>$user->school_id,
            ]);
          
        }
      
    
    }
      return [
          'status_code'=>201,
          'status'=>'success',
          'subjects'=>$subjectIDs];
    }



    public function delete_list(Request $request)
    {   Teachersubject::whereIn('id',$request->subjectIds)->delete();
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
