<?php

namespace App\Http\Controllers\API\Traits;

use App\Level_sub;
use App\Mark;
use App\Report;
use Illuminate\Http\Request;

trait ScoreTraits{
use CummulativeTrait;
use GradingTrait;


    public function default_store(Request $request)
    {
    $subject_id=$request->subject_id;
    $Nstudents= $request->number_of_students;
      $isAvgTotal=0;

        $marksArray=[];
    $report=Report::findOrFail($request->report_id);
   Mark::whereIn('report_id',[$request->report_id])->where([['arm_id',$request->arm_id],['subject_id',$request->subject_id]])->delete();

            $subject=Level_sub::where([['level_id',$report->level_id],['subject_id',$request->subject_id]])->first();
            $LevelScores=Mark::whereIn('subject_id',[$request->subject_id])->where([['level_id',$report->level_id],['arm_id',$request->arm_id]],)
             ->get();

              if($report->ca1Percent==100){
                $isAvgTotal=1;
              }
            for($i=0;$i<$Nstudents; ++$i){
                $total=$this->default_sum($request->test1[$i],$request->test2[$i],$request->test3[$i],$request->exams[$i]);
                    if($report->isMidterm>0){
                        $total=$this->Midterm($total,$report->scoreBase);
                     }

                $gradding= $this->grade($total,$report->gradinggroup_id,auth('api')->user()->school_id);

                $mark=[];
          $mark['student_id']=$request->student_id[$i];
          $mark['report_id']=$request->report_id;
          $mark['level_id']=$report->level_id;
          $mark['subject_id']=$request->subject_id;
          $mark['test1']=$request->test1[$i];
          $mark['test2']=$request->test2[$i];
          $mark['test3']=$request->test3[$i];
          $mark['exams']=$request->exams[$i];
          $mark['arm_id']=$request->arm_id;
          $mark['total']= $isAvgTotal > 0?round($total/2,2): $total;
          $mark['grade']=$gradding['grade'];
          $mark['narration']=$gradding['narration'];
          $mark['term_id']=$report->term_id;
          $mark['type']=$subject->type;
          $mark['mid_term']=$request->midterm?$request->midterm[$i]:0;
          $mark['report_type']=$report->type;
          $mark['is_history']=0;

            if($mark['total']>0){
           array_push($marksArray,$mark);
            }

          }
      $armScores=collect($marksArray)->sortByDesc('total')->values();
      $CurrentlevelScores=collect($LevelScores);

         $count=0;
         $score_array2=[];
        foreach($marksArray  as $score){

             $CAvg=0;
             $CGrade=['grade'=>'','narration'=>''];
             $CNarration='';
             $grand_total=0;
             $averageScore=collect($LevelScores)->where('student_id',$score['student_id'])->pluck('total');
             $averageScore=  collect($averageScore)->push($score['total'])->avg();
            $grand_total=collect($LevelScores)->where('student_id',$score['student_id'])->sum('total');
             $score['arm_subj_position']=$this->getRanking($armScores,$score['total']);
             //$score['class_subj_position']=$this->getRanking($CurrentlevelScores,$score['total']);


         $score['arm_max_score']=collect($marksArray)->max('total');
         $score['arm_min_score']=collect($marksArray)->min('total');
         $score['arm_avg_score']=round(collect($marksArray)->avg('total'),2);



               $CGrade=$this->grade(floatval($averageScore),$report->gradinggroup_id,auth('api')->user()->school_id);


          $score['cummulative_avg']=round($averageScore,2);
          $score['grand_total']=round(($grand_total+$score['total']),2);
          $score['cummulative_grade']=$CGrade['grade'];
          $score['cummulative_narration']=$CGrade['narration'];
         // ANNUALLY
         if($report->isCummulative>0){
          $score['annual_average']=$score['cummulative_avg'];
          $score['annual_total']=$score['cummulative_avg'];
          $score['annual_grade']=$score['cummulative_grade'];
          $score['annual_score']=$score['cummulative_avg'];
         }
            Mark::create($score);


    }
    if($report->term_id===3){
       $this->cummulativePerformance($request->report_id,$subject_id,'default_result');
    }


 return 'success';
}





public function default_sum($t1,$t2,$t3,$exams,$midterm=0){
    $sum=0;
    if(is_numeric($t1)){
      $sum=  $sum+ $t1;
    }
     if(is_numeric($t2)){
        $sum=  $sum+$t2;
    }
     if(is_numeric($t3)){
        $sum=  $sum+$t3;
    }
     if(is_numeric($exams)){
       $sum=  $sum+ $exams;
    }
    if(is_numeric($midterm)){
        $sum=  $sum+ $midterm;
     }


    return round($sum,2);
}



public function Midterm($total, $scoreBase){
    return round((($total/$scoreBase)*100),2);
}







}
