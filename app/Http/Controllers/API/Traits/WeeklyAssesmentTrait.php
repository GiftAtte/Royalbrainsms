<?php

namespace App\Http\Controllers\API\Traits;

use App\Has_arm;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Level;
use App\Level_sub;
use App\Mark;
use App\Models\WeeklyAssesment;
use App\Models\WeeklyResult;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait WeeklyAssesmentTrait{



public function loadWeeklyAssesment(Request $request){


             //return $request->all();
         $historyLevel=Level::where('is_history',1)->pluck('id');
         $arm=Has_arm::whereNotIn('level_id',$historyLevel)->where('staff_id',auth('api')->user()->staff_id)->first();

        $report_id=$request->report_id;
        $subject_id=$request->subject_id;
        $week_id=$request->week_id;
        $report=Report::findOrFail($request->report_id);

       return DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$request->arm_id]])
         ->leftJoin('weeklyassesments', function($join) use($report_id,$subject_id,$week_id)
         {
             $join->on('weeklyassesments.student_id', '=', 'students.id')
             ->where([['weeklyassesments.report_id',$report_id],
                      ['weeklyassesments.subject_id',$subject_id],
                      ['weeklyassesments.week_id',$week_id],
                    ]);
         })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
          students.id as student_id,weeklyassesments.test as test,weeklyassesments.class_work as class_work,
          weeklyassesments.subject_id as subject_id'))->orderBy('name')
        ->get();


        }



public function updateWeeklyScores(Request $request){
       // return $request->all();
     $arm_id=$request->arm_id;
     $subject_id=$request->subject_id;
     $week_id=$request->week_id;
     $report_id=$request->report_id;
      $scores=$request->scores;
     WeeklyAssesment::where([['report_id',$report_id],['week_id',$week_id],['subject_id',$subject_id]])->delete();
    //return WeeklyAssesment::where([['report_id',$report_id],['week_id',$week_id],['subject_id',$subject_id]])->get();

     foreach($scores as $score){
        if(!empty($score['class_work']) || !empty($score['test'])){
       $data=[
           'student_id'=>$score['student_id'],
           'report_id'=>$report_id,
           'week_id'=>$week_id,
           'subject_id'=>$subject_id,
           'class_work'=>$score['class_work'],
           'school_id'=>AppUtils::getSchoolId(),
           'test'=>$score['test'],
       ];

            WeeklyAssesment::create($data);
    }
     }


     return $this->computeWeeklyAssesment($report_id,$subject_id);

        //return 'success';
}


   public function computeWeeklyAssesment($report_id,$subject_id){



           $scores=WeeklyAssesment::where([['report_id',$report_id],['subject_id',$subject_id]])->get();
           WeeklyResult::where([['report_id',$report_id],['subject_id',$subject_id]])->delete();
           $scoreCollect=collect($scores);
            $studentIDs=collect($scores)
                   ->unique('student_id')
                   ->pluck('student_id')
                   ->toArray();

             $schoolId=AppUtils::getSchoolId();


              for($i=0;$i<count($studentIDs);++$i){

                $testAvg= $scoreCollect
                 ->where('student_id',$studentIDs[$i])
                   ->average('test');
                  $classWorkAvg= $scoreCollect
                 ->where('student_id',$studentIDs[$i])
                   ->average('class_work');

                   $data=[
                    'subject_id'=>$subject_id,
                    'report_id'=>$report_id,
                    'student_id'=>$studentIDs[$i],
                    'test_avg'=> round( $testAvg,2),
                    'class_work_avg'=>round( $classWorkAvg,2),
                    'school_id'=>$schoolId
                   ];

                 WeeklyResult::create($data) ;

              }
              return 'computed successfully';

   }





   public function loadStudentsWithWeekActivity( $report,$subject_id,$arm_id){

           $report_id=$report->id;
   //return[ $report-,$subject_id,$arm_id];

     $terminal=DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$arm_id]])
         ->leftJoin('marks', function($join) use($report_id,$subject_id)
         {
             $join->on('marks.student_id', '=', 'students.id')
             ->where([['marks.report_id',$report_id],
                      ['marks.subject_id',$subject_id]]);
         })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
          students.id as student_id, marks.test1 as test1, marks.test2 as test2, marks.test3 as test3,
          marks.exams as exams,marks.subject_id as subject_id, marks.note as note,marks.report_id as report_id'))->orderBy('name');
       //  ->get();
      // return collect($terminal->get());

        $list= DB::table('weeklyresults')->where([
             ['weeklyresults.report_id',$report_id],['weeklyresults.subject_id',$subject_id]])
             ->joinSub($terminal, 'terminals', function ($join){
                $join->on('weeklyresults.student_id', '=', 'terminals.student_id');
             })->select('terminals.name','terminals.student_id','terminals.arm_id','terminals.subject_id','terminals.exams','terminals.test3',
             'weeklyresults.class_work_avg as test1','weeklyresults.test_avg as test2')->distinct('student_id')
             ->orderBy('name')->get();
 //return collect($list)->unique('student_id')->values()->all();
                 if(count($list)>0){
                     return collect($list)->unique('student_id')->values()->all();
                 }else{
                     return collect($terminal->get());

                 }

   }


    public function deleteWeeklyActivity(Request $request){
        $report_id=$request->report_id;
        $week_id=$request->week_id;
        $subject_id=$request->subject_id;
        WeeklyAssesment::where([['report_id',$report_id],['week_id',$week_id],['subject_id',$subject_id]])->delete();
        WeeklyResult::where([['report_id',$report_id],['subject_id',$subject_id]])->delete();
        $this->computeWeeklyAssesment($report_id,$subject_id);
        return ;
    }
}
