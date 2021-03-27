<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Level;
use App\Level_history;
use App\School;
use App\Student;
use Dotenv\Result\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Level::where([['is_history',0],['school_id',auth('api')->user()->school_id]])->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promoteStudents(Request $request)
    {
        $old_id=$request->oldlevel_id;
        $new_id=$request->newlevel_id;
          //return $request->all();
       //DB::table('students')->whereIn('class_id',[$old_id])->update(['class_id'=>$new_id]);
       $students=Student::whereIn('class_id',[$old_id])->select('id','class_id','arm_id','surname','first_name','middle_name')->get();
       foreach($students as $student){
          $lvel=Level::findOrFail($student->class_id);
           Level_history::updateOrCreate(['student_id'=>$student->id,'level_id'=>$student->class_id,],[
               'name'=>$student->surname.' '.$student->first_name.' '.$student->middle_name,
               'student_id'=>$student->id,
               'level_id'=>$student->class_id,
               'arm_id'=>$student->arm_id,
               'level_name'=>$lvel->level_name
           ]);
       }
       DB::table('students')->whereIn('class_id',[$old_id])->update(['class_id'=>$new_id]);
        $level=Level::findOrFail($old_id);
        $level->is_promoted=1;
        $level->save();
        return ['message'=>'success'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getHistory()
    {
        return Level::with('staff')->where([['is_history',1],['school_id',auth('api')->user()->school_id]])->latest()->paginate(50);
    }

    public function getStudentHistory($id){
        return Level_history::with(['students','arm','levels'])->where('level_id',$id)->orderby('level_id')->orderby('arm_id')->paginate('50');
    }
    public function getNewStudents($id){
        $studentArr=[];
      $students=Level_history::whereIn('level_id',[$id])->pluck('student_id');
       return $newStudents= Student::whereIn('id',$students)
       ->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.id as id,class_id'))
      ->distinct('students.id') ->orderby('name')->get();
      foreach($newStudents as $newStudent){
          if(!empty($newStudent->name)){
              array_push($studentArr,$newStudent);
          }
      }
        return $studentArr;
    }

   public function assignArm(Request $request){
    $this->validate($request,[
        'level_id' => 'required',
        'arm_id' => 'required',


    ]);
       $level_id=$request->level_id;
       $arm_id=$request->arm_id;
       $students=$request->students;
       foreach($students as $student){
           $stu=Student::findOrFail($student['id']);
           $stu->class_id=$level_id;
           $stu->arm_id=$arm_id;
           $stu->save();
       }
       $old_level=Level::findOrFail($request->oldlevel_id);
       $old_level->assign_arm=1;
       $old_level->save();
       return ['message'=>'success'];
   }

    public function setHistory(Request $request)
    {
        $history=$request->history;
        $old_level= [];
        foreach($history as $h){
            array_push($old_level,$h['id']);
        }
         $current=$request->current;
         foreach($history as $level){
             $lvel=Level::findOrFail($level['id']);
             $lvel->is_history=1;
             $lvel->save();
         }



         foreach($current as $level){
            $lvel=Level::findOrFail($level['id']);
            $lvel->is_history=0;
            $lvel->is_promoted=0;
            $lvel->save();
           // DB::table('Level_histories')->whereIn('level_id', [$level['id']])->delete();

        }
         return ['message'=>'Success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function importHistoryLevel(Request $request)
    {



        $students=$request->csv;

        $is_promotion=intval($request->csv[0]['is_promotion']);
        $level_id=intval($request->csv[0]['level_id']);
        $current_levelId=intval($request->csv[0]['current_levelId']);
        $school_id=auth('api')->user()->school_id;
       // for promotion
        if($is_promotion===1){
            foreach($students as $student){
                Level_history::updateOrCreate(['student_id'=>$student['student_id'],'level_id' => $student['level_id']],[
                    'student_id'=>$student['student_id'],
                    'name' => $student['student_name'],
                    'level_id' => $student['level_id'],
                    'arm_id'=>$student['arm_id'],
                    'level_name' => 'level',
                    'school_id'=>$school_id,
                ]);

                Student::updateOrCreate(['id'=>$student['student_id']],[

                    'class_id' => $current_levelId,
                    'arm_id'=>$student['arm_id'],

                ]);


        }

        $level=Level::findOrFail($level_id);
        $level->is_history=1;
        $level->is_promoted=1;
        $level->assign_arm=1;
        $level->save();
    }
// for history
        else{
        foreach($students as $student){
             //$user =new User();

            Level_history::updateOrCreate(['student_id'=>$student['student_id'],'level_id' => $student['level_id']],[
                    'student_id'=>$student['student_id'],
                    'name' => $student['student_name'],
                    'level_id' => $student['level_id'],
                    'arm_id'=>$student['arm_id'],
                    'level_name' => 'level',
                    'school_id'=>$school_id,
                ]);
                $level_id=$student['level_id'];

    }
    $level=Level::findOrFail($level_id);
    $level->is_history=1;
    $level->save();
}
    
        return 'success';
    }



public function promotedArm($level_id,$arm_id){
   //$scoreCont=new ScoreController();
   return Student::with(['levels','arm'])->where([['class_id',$level_id],['arm_id',$arm_id]])->orderby('surname')->paginate(1000);

}
}
