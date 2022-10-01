<?php
namespace App\Http\Controllers\API\Traits;
use App\Mark;
use RankingTrait;

trait CummulativeTrait{

public function cummulativePerformance($report_id,$subject_id,$reportType){
            //  $level_marks=null;
         if($reportType==='terminal'){
                 $level_marks=Mark::whereIn('report_id',[$report_id])
                     ->whereNotIn('grand_total',[0])
                     ->where('subject_id',$subject_id)
                     ->select('id','student_id','annual_total as total','annual_position')
                     ->get();


                     $sortedScores=collect($level_marks)->sortByDesc('total')->values();
                     foreach($level_marks as $score){
                        $score->annual_position=$this->getRanking($sortedScores,$score['total']);
                          $score->save();
                     }
                      //$score['annual_position']=$this->annualRanking($armScores,$score['annual_total']);



                    }else{
       //$report=Report::findOrFail($report_id);
        $level_marks=Mark::whereIn('report_id',[$report_id])
                     ->whereNotIn('grand_total',[0])
                     ->where('subject_id',$subject_id)
                     ->select('id','student_id','average as total','annual_position')
                     ->get();

                     $sortedScores=collect($level_marks)->sortByDesc('total')->values();
                     foreach($level_marks as $score){
                        $score->annual_position=$this->getRanking($sortedScores,$score['total']);
                          $score->save();
                     }
         }
                     //$collectScores=collect($level_marks)->select('id,student_id, grand_total as total')->get();


    }




}
