<?php

namespace App\Http\Controllers\API;

use App\Attendance;
use App\Http\Controllers\Controller;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

   public function loadAttendance($report_id,$arm_id){
    $report=Report::findOrFail($report_id);

    return  DB::table('students')->where([['students.class_id',$report->level_id],['students.arm_id',$arm_id],['is_active',1]])
      ->leftJoin('attendances', function($join) use($report_id)
      {
          $join->on('attendances.student_id', '=', 'students.id')
          ->where('attendances.report_id',$report_id);
      })
      ->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,
       students.id as student_id, attendances.school_days as school_days, attendances.days_present as days_present'))->distinct('students.id')
      ->get();

  }

  public function createAttendance(Request $request ){
     
    $students=$request->students;
    $report_id=$request->report_id;
    $school_days=$request->school_days;

foreach($students as $student){
    if(!empty($student['days_present'])){
     Attendance::updateOrCreate(
        [
         'student_id'=>$student['student_id'],
         'report_id'=>$report_id,  
        ],
        [
            'school_days'=>$school_days,
            'days_present'=>$student['days_present'],
            'days_absent'=> intval($school_days)-intval($student['days_present'])
        ]
    );
    
}}
    
    return [
        'status_code'=>201,
        'status'=>'success',
        'students'=>$students
    ];
  }




  public function importAttendance(Request $request){
      if($request->has('file')){
        $data=array_map('str_getcsv',file($request->file));
        $headers=$data[0];
      unset($data[0]);
       foreach ($data as $row) {
          $student=array_combine($headers,$row);
          if(!empty($student['days_present']))
           Attendance::updateOrCreate(
        [
         'student_id'=>$student['student_id'],
         'report_id'=>$student['report_id'],  
        ],
        [
            'school_days'=>$student['school_days'],
            'days_present'=>$student['days_present'],
            'days_absent'=> intval($student['school_days'])-intval($student['days_present'])
        ]
    );
           
       }
           return [
        'status_code'=>201,
        'status'=>'success',
        
    ];
      }
  }








}
