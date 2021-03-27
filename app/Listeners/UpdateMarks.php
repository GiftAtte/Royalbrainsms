<?php

namespace App\Listeners;

use App\Mark;
use App\Grading;
use App\Report;
use App\Student;
use App\Result;
use App\Events\MarksCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\API\ScoreController;
use App\Level_history;

class UpdateMarks 
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MarksCreated  $event
     * @return void
     */
    public function handle(MarksCreated $event)
    {
       $scoreController=new ScoreController();
        $max_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$event->report_id],['subject_id',$event->subject_id]])->max('total');
        $min_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$event->report_id],['subject_id',$event->subject_id]])->min('total');


        $class_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$event->report_id],['subject_id',$event->subject_id]])->avg('total');

        // positioning
        $class_sub_position='';
      foreach($event->students as $student){

     $cummulative_avg=DB::table('marks')->whereNotIn('total',[0])->where([['level_id',$event->level_id],['subject_id',$event->subject_id],['student_id',$student['student_id']]])->avg('total');
     $grand_total=DB::table('marks')->where([['level_id',$event->level_id],['subject_id',$event->subject_id],['student_id',$student['student_id']]])->sum('total');
     $subject_positions=$scoreController->getSubjectRank($student['student_id'],$event->report_id,$event->subject_id);
     $subject_position_arm=$scoreController->getSubjectRank($student['student_id'],$event->report_id,$event->subject_id,$student['arm_id']);
     $arm_max_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$event->subject_id],['report_id',$event->report_id],['arm_id',$student['arm_id']]])->max('total');
     $arm_min_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$event->subject_id],['report_id',$event->report_id],['arm_id',$student['arm_id']]])->min('total');
     $arm_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$event->subject_id],['report_id',$event->report_id],['arm_id',$student['arm_id']]])->avg('total');
        $class_sub_position=$subject_positions['position'];
        $arm_sub_position= $subject_position_arm['position'];

        DB::table('marks')
        ->where([['report_id', $event->report_id],['student_id',$student['student_id']],['subject_id',$event->subject_id]])
        ->updateOrInsert(
          ['report_id' => $event->report_id, 'student_id' => $student['student_id']],
          ['class_avg_score' =>round($class_avg_score,2),
                    'class_subj_position'=>$class_sub_position,
                    'max_class_score'=>$max_score,
                    'min_class_score'=>$min_score,
                             'average'=>round($cummulative_avg,2),
                             'grand_total'=>round($grand_total,2),
                             'arm_avg_score'=>round($arm_avg_score,2),
                             'arm_min_score'=>round($arm_min_score,2),
                             'arm_max_score'=>round($arm_max_score,2)


        ]
      );

      $mark=Mark::where([['subject_id',$event->subject_id],['report_id',$event->report_id],['student_id',$student['student_id']]])->first();
      $mark->arm_subj_position=$arm_sub_position;
      $mark->save();
    }
    $this->resultSummary($event->report_id,$event->is_history);
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
                 $student_grade = $this->grade($average);
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

public function grade($score)
{
    if(is_numeric($score)){
        $score=round($score,2);
    $grading=Grading::where([['lower_bound','<=',$score],['upper_bound','>=',$score],['school_id',auth('api')->user()->school_id]])->first();
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
