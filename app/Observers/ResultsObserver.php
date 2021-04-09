<?php

namespace App\Observers;

use App\CheckResult;
use App\Mark;
use Illuminate\Support\Facades\DB;
use App\Student;
use App\Result;
use App\Report;
use App\Grading;
use App\Http\Controllers\API\ScoreController;
use App\Level_history;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResultsObserver implements ShouldQueue
{

    public function created(CheckResult $checkreport)
    {             $scoreController=new ScoreController();
               $students=Mark::whereNotIn('total',[0])->where('report_id',$checkreport->report_id)->select('student_id','arm_id')->distinct('student_id')->get();
                  foreach($students as $student){
                    $scoreController->resultSummary($checkreport->report_id,$student->student_id,$student->arm_id,$checkreport->school_id);
                  }
               // $this->resultSummary($checkreport->report_id,$checkreport->is_history);
    }

    /**
     * Handle the mark "updated" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function updated(CheckResult $checkreport)
    {
        $this->resultSummary($checkreport->report_id,$checkreport->is_history);
    }

    /**
     * Handle the mark "deleted" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function deleted(CheckResult $report)
    {
        //
    }

    /**
     * Handle the mark "restored" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function restored(CheckResult $checkreport)
    {
        //
    }

    /**
     * Handle the mark "force deleted" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function forceDeleted(CheckResult $mark)
    {
        //
    }


    public function resultSummary($report_id,$is_history)
    {
      // $scores=Mark::where('report_id',$id)->get();

      $report=Report::findOrFail($report_id);

      $students=Mark::whereNotIn('total',[0])->where([['report_id',$report_id]])->select('student_id','arm_id','level_id')->distinct('student_id')->get();
      foreach($students as $student){

      $students_arm=Mark::where([['report_id',$report_id],['arm_id',$student->arm_id]])->whereNotIn('total',[0])
                       ->select('student_id')->distinct('student_id')->get();

      //$student_by_arm=Student::where
      $level_id=$student->level_id;
      $total_student=count($students_arm);
    // return $level_id;

        $cummulative_avg=Mark::whereNotIn('total',[0])->where([['level_id',$level_id],['student_id',$student->student_id],['type','Academic']])->avg('total');

        $average=Mark::whereNotIn('total',[0])->where([['report_id',$report_id],['student_id',$student->student_id],['type','Academic']])->avg('total');
                 $total_scores=Mark::where([['report_id',$report_id],['student_id',$student->student_id],['type','Academic']])->sum('total');
                     // $gradding = new Gradding();
                 $student_grade = $this->grade($average,$report->gradinggroup_id);
                         $grade = $student_grade['grade'];
                    $narration = $student_grade['narration'];


                    Result::updateOrInsert(
                        ['report_id' => $report_id, 'student_id' => $student->student_id],
                        [
                          'student_id'=>$student->student_id,
                          'report_id'=>$report_id,
                          'level_id'=>$level_id,
                          'total_scores'=>round($total_scores,2),
                                'grade'=>$grade,
                                'average_scores'=>round($average,2),
                                'narration'=>$narration,
                                'total_students'=>$total_student,
                                'cummulative_average'=>round($cummulative_avg,2),
                                'class_position'=>$this->studentPosition($student->student_id,$report_id,false,$is_history),
                                'arm_position'=>$this->studentPosition($student->student_id,$report_id,true,$is_history),
                       ]
                    );
}

    }

public function grade($score,$gradinggroup_id)
{
    if(is_numeric($score)){
        $score=round($score,2);
        $grading=Grading::whereIn('group_id',[$gradinggroup_id])->where([['lower_bound','<=',$score],['upper_bound','>=',$score],['school_id',auth('api')->user()->school_id]])->first();
    if($grading){
   $Grade=$grading->grade;
   $credite_point=$grading->credit_point;
   $narration=$grading->narration;

  return ["grade"=>$Grade,"narration"=>$narration,'credit_point'=>$credite_point,'total'=>$score];
}}
return ["grade"=>'F',"narration"=>'','credit_point'=>0,'total'=>0];
}

  // Class and arm positioning ===sorting students
  public function studentPosition($id,$report_id,$arm=false,$is_history){
    $report=Report::findOrFail($report_id);
    $student=Student::findOrFail($id);
    $students=null;
    if($is_history>0){
        if($arm){
            $students=Level_history::select('student_id')->where([['level_id',$report->level_id],['arm_id',$student->arm_id]])->get();
          }
          else{
          $students=Level_history::select('student_id')->where('level_id',$report->level_id)->get();
          }
    }else{
    if($arm){
      $students=Student::select('id')->where([['class_id',$report->level_id],['arm_id',$student->arm_id]])->get();
    }
    else{
    $students=Student::select('id')->where('class_id',$report->level_id)->get();
    }}
    $studentArr=array();
    $studentPosition=array();
    foreach($students as $student){
    $avgScores=DB::table('marks')
    ->select(DB::raw('avg(total) as Total'),'student_id')
    -> where([['student_id',$student->id],['type','Academic'],
    ['report_id',$report_id]

    ])
     ->groupBy('student_id')
    ->get();

    foreach($avgScores as $avgScore){
    array_push($studentArr,$avgScore);
       rsort($studentArr);
    }}
    // foreach($students as $student){
    //   array_push($studentPosition,  $this->Rank3($studentArr,$id));
    // }
    return $this->Rank3($studentArr,$id);
    }

    // class positionioning/arm positioning Ranking
    public function Rank3($positions=array() ,$id){
                     $rank=1;
                     $total=0;
                      $rank2=0;
                      $counter=1;

                    foreach($positions as $position) {


                            if($position->Total==$total)
                            {    $rank=$rank-$counter; $rank2=$rank;$counter=++$counter;}


                            if($position->student_id==$id){
                              return$this->ordinal($rank);
                              break;
                                  }


          $total=$position->Total;
          if($rank2==$rank){$rank=$rank+$counter;}else{
       $rank=++$rank;
       $counter=1;
          }

      }


      }
      public function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        } else {
            return $number . $ends[$number % 10];
        }
    }



}
