<?php
namespace App\Http\Controllers\API\Traits;
use App\Grading;

trait GradingTrait{




public function grade($score,$gradinggroup_id,$school_id)
{
   // $user_school=auth('api')->user()->school_id;
    if(is_numeric($score)){
        $score=round($score,2);
    $grading=Grading::whereIn('group_id',[$gradinggroup_id])->where([['lower_bound','<=',$score],['upper_bound','>=',$score],['school_id',$school_id]])->first();
    if($grading){
   $Grade=$grading->grade;
   $progress=$grading->progress_status;
   $narration=$grading->narration;

  return ["grade"=>$Grade,"narration"=>$narration,'progress_status'=>$progress,'total'=>$score];
}}
return ["grade"=>'-',"narration"=>'-','progress_status'=>0,'total'=>0];
}



}
