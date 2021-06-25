<?php

namespace App\Http\Controllers\API;

use App\Has_arm;
use App\Staff_comment;
use App\TeachersComment;
use App\Report;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffcommentController extends Controller
{

   public function __construct()
   {
       $this->middleware('auth:api');
   }

   public function index()
   {
           $user=auth('api')->user();
           if($user->type==='tutor'){
               $level=Has_arm::where('staff_id',$user->staff_id)->first();
            return Staff_comment::where([['school_id',$user->school_id],['level_id',$level->level_id],['arm_id',$level->arms_id]])
            ->latest()->paginate(10);
           }
       return Staff_comment::where('school_id',auth('api')->user()->school_id)
       ->latest()->paginate(10);
   }


   public function getComments()
   {
       return Staff_comment::where('school_id',auth('api')->user()->school_id)->get();

   }

   public function store(Request $request)
   {


       $this->validate($request,[

           'comment' => 'required',

       ]);
       $level=Has_arm::where('staff_id',auth('api')->user()->staff_id)->first();
       return Staff_comment::create([

           'comment' => $request->comment,

           'school_id' => auth('api')->user()->school_id,
       ]);
   }

   public function update(Request $request)
   {
       $this->validate($request,[

            'comment' => 'required',


       ]);
      $grading=Staff_comment::findOrFail($request->id);
      $grading->update($request->all());
   }


   public function destroy($id)
   {
      $grading=Staff_comment::findOrFail($id);
      $grading->delete();
   }


   public function loadComments(Request $request){
   //return $request->all();
    $report=Report::findOrFail($request->report_id);
    $report_id=$request->report_id;
    return  DB::table('students')->where([['students.class_id',$report->level_id],['students.arm_id',$request->arm_id]])

      ->leftJoin('teachers_comments', function($join) use($report_id)
      {
          $join->on('teachers_comments.student_id', '=', 'students.id')
          ->where('teachers_comments.report_id',$report_id);
      })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,
       students.id as student_id, teachers_comments.comment_id as comment_id

       '))->distinct('students.id')->distinct('report.id')
      ->get();
    }
    public function assignComment(Request $request){
        $students=$request->number_of_students;
        $report=Report::findOrFail($request->report_id);
       for ($i=0;$i<$students;++$i){
           TeachersComment::where([['report_id',$report->id],['student_id',$request->student_id[$i]]])->delete();
           TeachersComment::create(

           [
           'student_id'=>$request->student_id[$i],
           'report_id'=>$request->report_id,
           'comment_id'=>$request->comment_id[$i],
           'level_id'=>$report->level_id,
           'arm_id'=>$request->arm_id
           ]);}
}
}
