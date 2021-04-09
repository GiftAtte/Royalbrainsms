<?php

namespace App\Observers;

use App\Markcheck;
use App\Http\Controllers\API\ScoreController;
use App\Mark;
use Illuminate\Support\Facades\DB;
use App\Student;
use App\Result;
use App\Report;
use App\Grading;
use Illuminate\Contracts\Queue\ShouldQueue;
class MarksObserver implements ShouldQueue
{
    /**
     * Handle the mark "created" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function created(Markcheck $markcheck)
    {

        //$report=Report::findOrFail($markcheck->report_id);
        $students=Mark::whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->select('student_id','level_id','arm_id')->get();
       $scoreController=new ScoreController();
       $max_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->max('total');
       $min_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->min('total');
       $class_scores=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->select('total')->get()->toArray();

       $class_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->avg('total');

       // positioning
      // $class_sub_position='';
     foreach($students as $student){
          $score=Mark::where([['student_id',$student->student_id],['report_id',$student->report_id],['subject_id',$student->subject_id]])->select('total')->first();
          $arm_scores=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id],['arm_id',$student->arm_id]])->select('total')->get()->toArray();

    $cummulative_avg=DB::table('marks')->whereNotIn('total',[0])->where([['level_id',$student->level_id],['subject_id',$markcheck->subject_id],['student_id',$student->student_id]])->avg('total');
    $grand_total=DB::table('marks')->whereNotIn('total',[0])->where([['level_id',$student->level_id],['subject_id',$markcheck->subject_id],['student_id',$student->student_id]])->sum('total');
    $subject_positions=$scoreController->getRank($score->total,$class_scores);
    $subject_position_arm=$scoreController->getRank($score->total,$arm_scores);
    $arm_max_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->max('total');
    $arm_min_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->min('total');
    $arm_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->avg('total');
      //  $class_sub_position=$subject_positions['position'];
      //  $arm_sub_position= $subject_position_arm['position'];

       DB::table('marks')
       ->where([['report_id', $markcheck->report_id],['student_id',$student->student_id],['subject_id',$markcheck->subject_id]])
       ->updateOrInsert(
         ['report_id' => $markcheck->report_id, 'student_id' => $student->student_id],
         ['class_avg_score' =>round($class_avg_score,2),
                   'class_subj_position'=> $subject_positions,
                   'arm_subj_position'=>$subject_position_arm,
                   'max_class_score'=>$max_score,
                   'min_class_score'=>$min_score,
                            'average'=>round($cummulative_avg,2),
                            'grand_total'=>round($grand_total,2),
                            'arm_avg_score'=>round($arm_avg_score,2),
                            'arm_min_score'=>round($arm_min_score,2),
                            'arm_max_score'=>round($arm_max_score,2)


       ]
     );

    //  $mark=Mark::where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['student_id',$student->student_id]])->first();
    //  $mark->arm_subj_position=$arm_sub_position;
    //  $mark->save();
   }
   }



    /**
     * Handle the mark "updated" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function updated(Markcheck $markcheck)
    {
        $students=Mark::whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->select('student_id','level_id','arm_id')->get();
       $scoreController=new ScoreController();
       $max_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->max('total');
       $min_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->min('total');


       $class_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$markcheck->report_id],['subject_id',$markcheck->subject_id]])->avg('total');

       // positioning
       $class_sub_position='';
     foreach($students as $student){

    $cummulative_avg=DB::table('marks')->whereNotIn('total',[0])->where([['level_id',$student->level_id],['subject_id',$markcheck->subject_id],['student_id',$student->student_id]])->avg('total');
    $grand_total=DB::table('marks')->where([['level_id',$student->level_id],['subject_id',$markcheck->subject_id],['student_id',$student->student_id]])->sum('total');
    $subject_positions=$scoreController->getRank($student['student_id'],$markcheck->report_id,$markcheck->subject_id);
    $subject_position_arm=$scoreController->getRank($student['student_id'],$markcheck->report_id,$markcheck->subject_id,$student['arm_id']);
    $arm_max_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->max('total');
    $arm_min_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->min('total');
    $arm_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->avg('total');
       $class_sub_position=$subject_positions['position'];
       $arm_sub_position= $subject_position_arm['position'];

       DB::table('marks')
       ->where([['report_id', $markcheck->report_id],['student_id',$student->student_id],['subject_id',$markcheck->subject_id]])
       ->updateOrInsert(
         ['report_id' => $markcheck->report_id, 'student_id' => $student->student_id],
         ['class_avg_score' =>round($class_avg_score,2),
                   'class_subj_position'=>$class_sub_position,
                   'arm_subj_position'=>$arm_sub_position,
                   'max_class_score'=>$max_score,
                   'min_class_score'=>$min_score,
                            'average'=>round($cummulative_avg,2),
                            'grand_total'=>round($grand_total,2),
                            'arm_avg_score'=>round($arm_avg_score,2),
                            'arm_min_score'=>round($arm_min_score,2),
                            'arm_max_score'=>round($arm_max_score,2)


       ]
     );

    //  $mark=Mark::where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['student_id',$student->student_id]])->first();
    //  $mark->arm_subj_position=$arm_sub_position;
    //  $mark->save();
   }
    }

    /**
     * Handle the mark "deleted" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function deleted(Markcheck $markcheck)
    {
        //
    }

    /**
     * Handle the mark "restored" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function restored(Markcheck $markcheck)
    {
        //
    }

    /**
     * Handle the mark "force deleted" event.
     *
     * @param  \App\Mark  $mark
     * @return void
     */
    public function forceDeleted(Markcheck $markcheck)
    {
        //
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
  public function studentPosition($id,$report_id,$arm=false){
    $report=Report::findOrFail($report_id);
    $student=Student::findOrFail($id);
    $students=null;
    if($arm){
      $students=Student::select('id')->where([['class_id',$report->level_id],['arm_id',$student->arm_id]])->get();
    }
    else{
    $students=Student::select('id')->where('class_id',$report->level_id)->get();
    }
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
