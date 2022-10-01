<?php
namespace App\Http\Controllers\API\Traits;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Models\LevelAttendance;
use App\Report;
use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait AttendanceTrait{



    public function addAttendance(Request $request){
             //return $request->students;

              $attendance=$request->students;
              $date=$request->attendance_date;
              $report_id=$request->report_id;
              $level_id=Report::findOrFail($report_id)->level_id;
               LevelAttendance::where([['report_id',$report_id],
                                     ['attendance_date',$date]])
                                     ->where('arm_id',$request->arm_id)
                                     ->delete();

              for($i=0;$i<count($attendance);++$i){
                $data=[
                    'student_id'=>$attendance[$i],
                    'isPresent'=>1,
                    'attendance_date'=>$date,
                    'report_id'=>$report_id,
                     'school_id'=>AppUtils::getSchoolId(),
                     'level_id'=>$level_id,
                     'arm_id'=>$request->arm_id

                ];

                   LevelAttendance::create($data);

              }
              return 'success';
    }

       public function loadStudentAttendance(Request $request){

                    $level_id=Report::findOrFail($request->report_id)->level_id;
                      $check=LevelAttendance::where([['report_id',$request->report_id],
                                     ['attendance_date',$request->attendance_date]])->count();

                                     if($check>0){
                                return DB::table('students')
                              ->where([['students.class_id',$level_id],['students.arm_id',$request->arm_id]])
                              ->leftJoin('levelattendance','students.id','=','levelattendance.student_id')
                              ->selectRaw('concat(students.surname," ",students.first_name) as name,
                                students.id as student_id, levelattendance.isPresent as isPresent')
                                ->distinct('student_id')
                                ->get();
                                     }
                                     else{
                                    return DB::table('students')
                              ->where([['students.class_id',$level_id],['arm_id',$request->arm_id]])
                              ->selectRaw('concat(students.surname," ",students.first_name) as name,
                                students.id as student_id')
                                ->get();
                                     }
       }


       public function getAttendanceCount(Request $request){


            $attendance=$request->attendance;
              $date=$request->attendance_date;
              $report_id=$request->report_id;
               $level_id=Report::findOrFail($report_id)->level_id;
              $totalStudents=Student::where([['class_id',$level_id],['arm_id',$request->arm_id]])->count();
             $attendance= LevelAttendance::where([['report_id',$report_id],
                                     ['attendance_date',$date]])
                                     ->get();

             $numPresent=collect($attendance)->where('isPresent',1)->count();
             $numAbsent=$totalStudents-$numPresent;
                              $todaAttendance=DB::table('students')
                              ->where([['students.class_id',$level_id],['students.arm_id',$request->arm_id]])
                              ->leftJoin('levelattendance', function($join) use($date){
                                $join->on('students.id','=','levelattendance.student_id')
                                 ->where('levelattendance.attendance_date',$date);
                                   })
                              ->selectRaw('concat(students.surname," ",students.first_name) as name,
                                levelattendance.student_id as student_id, levelattendance.isPresent as isPresent, levelattendance.attendance_date')
                                ->orderBy('name')
                                ->get();
             return [
                'studentAttendance'=>$todaAttendance,
                'numPresent'=>$numPresent,
                'numAbsent'=>$numAbsent
             ];

                                    }



    public function getAttendanceByMonth(Request $request){

                         $report_id=$request->report_id;
                         $level_id=Report::findOrFail($report_id)->level_id;
                         $month=$request->month;
                         $year=$request->year;

                              $attendance=DB::table('students')
                              ->where([['students.class_id',$level_id],['students.arm_id',$request->arm_id]])
                              ->leftJoin('levelattendance', function($join) use($month,$year,$report_id){
                                $join->on('students.id','=','levelattendance.student_id')
                                ->where('levelattendance.report_id',$report_id)
                                ->whereYear('levelattendance.attendance_date',$year)
                                ->whereMonth('levelattendance.attendance_date',$month);
                                   })
                              ->selectRaw('concat(students.surname," ",students.first_name) as name,
                                levelattendance.student_id as student_id, levelattendance.isPresent as isPresent, levelattendance.attendance_date')
                                ->orderBy('name')
                                ->get();



            // return $attendance;
            // return collect($attendance)->groupBy('student_id');

             $studentAttendance=collect($attendance)->groupBy('student_id');
              return $this->sortAttendance($year,$month,$studentAttendance);


    }



      public function sortAttendance($year, $month,$attendance){
         //     return $attendance;
        $daysInMonth=Carbon::create($year,$month,1)->daysInMonth;
         $daysArray=[];
         for($i=1;$i<=$daysInMonth;++$i){

                   $days=[
                    'date'=>Carbon::create($year,$month,$i)->toDateString(),
                    'dayOfMonth'=>$i,
                    'dayOfWeek'=>Carbon::create($year,$month,$i)->format('D'),
                    'month'=>Carbon::create($year,$month,$i)->month

                   ];
                array_push($daysArray,$days);

         }
       $attendanceArr=[];
       $isPresent=false;

         foreach($attendance as $key=>$student){
            $name=$student[0]->name;
            $studentRec=[];

             foreach($daysArray as $day){
             $dateStr=$day['date'];
             $attendanceInfo=$student->firstWhere('attendance_date',$dateStr);
             if(!empty($attendanceInfo)){
                $isPresent=true;
                 array_push($studentRec,[
                     'isPresent'=>1,
                     'date'=>$dateStr,
                     'dayOfMonth'=>$day['dayOfMonth'],
                     'dayOfWeek'=>$day['dayOfWeek'],
                     $day['dayOfMonth']=>$day['dayOfWeek']
                 ]) ;

             }else{
                 array_push($studentRec,[
                     'isPresent'=>0,
                     'date'=>$dateStr,
                     'dayOfMonth'=>$day['dayOfMonth'],
                     'dayOfWeek'=>$day['dayOfWeek'],
                     $day['dayOfMonth']=>$day['dayOfWeek']
                 ]) ;
             }



         }

            array_push($attendanceArr,[
                'id'=>$key,
                'name'=>$name,
                'attendance'=>$studentRec
            ]);
        }

         return ['attendance'=>$attendanceArr,
                'daysOfMonth'=>$daysArray,
                'month'=>$daysArray[0]['month']

            ];


      }


}
