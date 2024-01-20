<?php

namespace App\Http\Controllers\API\Traits;

use App\Level;
use App\Level_history;
use App\Mark;
use App\Report;
use App\Result;
use App\Student;
use Illuminate\Support\Facades\DB;

trait SummaryTrait{
use GradingTrait;

 public function resultSummary($report_id,$student_id,$arm_id,$school_id)
    {
        Result::where([['report_id',$report_id],['student_id',$student_id]])->delete();

        // $avg=DB::table('marks')->whereNotIn('total',[0])
        // -> where([['student_id',$student->id],['type','Academic'],
        // ['report_id',$report_id]

        // ])->avg('total');



        $report=Report::findOrFail($report_id);

      $total_student=Student::where([['class_id',$report->level_id],['arm_id',$arm_id]])->count();

      $level_id=$report->level_id;
        $cummulative_avg=DB::table('marks')->whereNotIn('total',[0])->where([['level_id',$level_id],['student_id',$student_id],['type','Academic']])->avg('total');

        $average=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$report_id],['student_id',$student_id],['type','Academic']])->avg('total');
                 $total_scores=DB::table('marks')->where([['report_id',$report_id],['student_id',$student_id],['type','Academic']])->sum('total');
                     // $gradding = new Gradding();
                 $student_grade = $this->grade($average,$report->gradinggroup_id,$school_id);
                         $grade = $student_grade['grade'];
                    $narration = $student_grade['narration'];


                    Result::create(
                        [
                          'student_id'=>$student_id,
                          'report_id'=>$report_id,
                          'level_id'=>$level_id,
                          'total_scores'=>round($total_scores,2),
                                'grade'=>$grade,
                                'average_scores'=>round($average,2),
                                'narration'=>$narration,
                                'total_students'=>$total_student,
                                'cummulative_average'=>round($cummulative_avg,2),
                                'class_position'=>'-',
                                'arm_position'=>'',
                                'arm_id'=>$arm_id,
                       ]
                    );
}






public function computeSummary($report_id,$arm_id){

$report=Report::findOrFail($report_id);
$scores=Mark::whereIn('report_id',[$report_id])->where([['arm_id',$arm_id],['total','>',0],['type','Academic']])->get();
if(count($scores)>0){
$cummulativeScores=Mark::whereIn('level_id',[$report->level_id])->whereNotIn('report_type',['default-midterm','mid_term'])
->whereNotIn('total',[0])->where([['type','Academic'],['arm_id',$arm_id]])->get();
$students=collect($scores)->unique('student_id')->pluck('student_id');

$scoreArr=[];
for($i=0;$i<count($students);++$i){
$annual_average=0;
$annual_total=0;
$averageScore=collect($scores)->where('student_id',$students[$i])->whereNotIn('total',[0])->avg('total');
$total=collect($scores)->where('student_id',$students[$i])->sum('total');


if($report->type==='terminal'){
$annual_total=collect($scores)->where('student_id',$students[$i])->sum('annual_total');
$annual_average=collect($scores)->where('student_id',$students[$i])->average('annual_total');
}else{
    $annual_total=collect($scores)->where('student_id',$students[$i])->sum('total');
    $annual_average=collect($scores)->where('student_id',$students[$i])->average('total');
}
$cummulative_avg=collect($cummulativeScores)->where('student_id',$students[$i])->avg('total');
 count($students);
if($total>0){
array_push($scoreArr,
  ['student_id'=>$students[$i],
  'average_scores'=>round($averageScore,2),
   'total_scores'=>round($total,2),
   'cummulative_average'=>round($cummulative_avg,2),
   'report_id'=>$report_id,
    'level_id'=>$report->level_id,
    'total_students'=>count($students),
    'total'=>round($averageScore,2),
    'annual_total'=>round($annual_total,2),
    'annual_average'=>round($annual_average,2)

   ]);

}
}
$results=[];
  $ScoreCollects=collect($scoreArr)->sortByDesc('total')->values();
  Result::whereIn('report_id',[$report_id])->where('arm_id',$arm_id)->delete();
foreach($scoreArr as $score){
    $grade=$this->grade($score['average_scores'],$report->gradinggroup_id,auth('api')->user()->school_id);
    $score['arm_position']=$this->getRanking($ScoreCollects,$score['total']);
    $score['arm_id']=$arm_id;
    $core['classAvg']=round(($ScoreCollects->average('total')/100),2);
    $score['grade']=$grade['grade'];
    $score['narration']=$grade['narration'];
   // return $report->type;
     if($report->isCummulative>0){
      $score['progress_status']=$this->grade($score['annual_average'],$report->gradinggroup_id,auth('api')->user()->school_id)['progress_status'];
      $score['annual_grade']=$this->grade($score['annual_average'],$report->gradinggroup_id,auth('api')->user()->school_id)['grade'];
      $score['annual_position']=$this->getRanking($ScoreCollects,$score['annual_average']);
      $score['totalstudents_overall']=count($scoreArr) ;
    }else{
     $score['progress_status']=$this->grade($score['cummulative_average'],$report->gradinggroup_id,auth('api')->user()->school_id)['progress_status'];
     }
//return $score;
     Result::create($score);

///return $score;

}

if($report->term_id===3){
   $scores=Result::whereIn('report_id',[$report_id])
   ->select('annual_average as total','id','annual_position','total_students')
   ->get();

   $sortedScore=collect($scores)->sortByDesc('total')->values();
     foreach($scores as $score){
           $score->annual_position=$this->getRanking($sortedScore,$score->total);
           $score->totalstudents_overall=collect($scores)->count();
           $score->maxAvg=collect($scores)->max('classAvg');
           $score->save();

     }
}

  return ['message'=>'success'];

                }else{
                    return['message'=>'no record'];
                }
}










public function resultSummary2($report_id)
{

    $aStudents=null;
  $report=Report::findOrFail($report_id);
 $is_history=Level::findOrFail($report->level_id)->is_history;
 if($is_history===0){
    $aStudents=Student::where('class_id',$report->level_id)->pluck('id');
 }else{
    $aStudents=Level_history::where('level_id',$report->level_id)->pluck('student_id');
 }
 // $aStudents=Student::where('class_id',$report->level_id)->pluck('id');
 $students=Mark::whereNotIn('average',[0])->where('report_id',$report_id)->whereIn('student_id',$aStudents)->select('student_id','arm_id')->distinct('student_id')->get();
  //$students=Mark::whereNotIn('total',[0])->where([['report_id',$report_id]])->select('student_id','arm_id','level_id')->distinct('student_id')->get();
  if(count($students)>0){
  foreach($students as $student){
    Result::where([['report_id',$report_id],['student_id',$student->student_id]])->delete();
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
             $student_grade = $this->grade($average,$report->gradinggroup_id,$student->school_id);
                     $grade = $student_grade['grade'];
                $narration = $student_grade['narration'];

                Result::where([['report_id',$report_id],['student_id',$student->student_id]])->delete();
                Result::create([
                      'student_id'=>$student->student_id,
                      'report_id'=>$report_id,
                      'level_id'=>$level_id,
                      'total_scores'=>round($total_scores,2),
                            'grade'=>$grade,
                            'average_scores'=>round($average,2),
                            'narration'=>$narration,
                            'total_students'=>$total_student,
                            'cummulative_average'=>round($cummulative_avg,2),
                            'class_position'=>'-',
                            //'class_position'=>$this->studentPosition($student->student_id,$report_id,false,$is_history),
                            'arm_position'=>$this->studentPosition($student->student_id,$report_id,true,$is_history),
                   ]
                );
}
return ['message'=>'success'];
}else{
  return ['message'=>'no record'];
}}




}
