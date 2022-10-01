<?php
namespace App\Http\Controllers\API\Traits;

use App\Arm;
use App\Assessment;
use App\Attendance;
use App\Comment;
use App\CrecheDomain;
use App\CrechestudentDomain;
use App\Grading;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Level_sub;
use App\Mark;
use App\PrincipalComent;
use App\Report;
use App\Result;
use App\Result_activation;
use App\Student;
use App\TeachersComment;
use App\User;


trait ResultTraits{




public function studenResult( $report_id, $student_id=null)
{
    //Mark::whereIn('report_id',[314,315,317,318,377,378,379,380,0])->delete();
    $scoreArr=[];
    $scores=null;
    $summary=null;
    $avgReport=[];
     $report=Report::with(['levels','sessions','terms'])->where('id',$report_id)->first();
 // return Mark:: where([['subject_id',98],['level_id',28]])->whereNotIn('report_id',[140,141,22])->get();
    $principal_sign=User::where([['type','principal'],['school_id',auth('api')->user()->school_id]])->select('photo')->first();
      if($student_id==='null'||$student_id===null){
        $user=auth('api')->user();
          if($user->type==='parent'){
        $student_id=Student::where([['class_id',$report->level_id],['parent_id',$user->parent_id]])->first()->id;
          }else{
               $student_id=$user->student_id;
          }

      }


        $comment=Result_activation::where([['report_id',$report_id],['student_id',$student_id]])->first();
        $attendance=Attendance::where([['student_id',$student_id],['report_id',$report_id]])->first();

        if($report->type==='blue_ridge'){
            $avgReport=$this->blueRidgeSummary($report,$student_id);
        }


          $pastTotal=AppUtils::getPastTotal($student_id,$report);
          $pastTotalarray=[];
          foreach ($pastTotal as $total ) {

              array_push($pastTotalarray,['subject_id'=>$total->subject_id,'term_id'=>$total->term_id,'total'=>$total->annual_score]);
              # code...
          }
          $collect= collect($pastTotalarray)->all();


  $level_sub=Level_sub::where('level_id',$report->level_id)->pluck('subject_id');
      //Mark::where('report_id',$report_id)->whereNotIn('subject_id',$level_sub)->distinct('subject_id')->delete();

      $subjectDropt=null;
    if($report->isCummulative){
              $subjectDropt=$this->getSubjectDroped($report,$student_id,$level_sub);
    }
      $student=Student::findOrFail($student_id);
     $arm=Arm::findOrFail($student->arm_id);
    // $this->resultSummary($report_id, $student_id,$student->arm_id);
    $user=User::with(['students','school'])->where('student_id',$student_id)->first();
    $grading=Grading::whereIn('group_id',[$report->gradinggroup_id])->where('school_id',$user->school_id)->get();

    $creche=$this->getCrecheComment($report,$student->id);
   if($report->type==='creche'){
    $scores=$creche['crecheComment'];
    $summary=$creche['summary'];

     }else{
      $scores=Mark::whereIn('report_id',[$report_id])->with('subjects')
     ->where([['student_id',$student_id],['type','Academic']])->whereNotIn('total',[0])
     ->distinct('subject_id')->get();
     $summary=Result::with(['student'])->where([['report_id',$report_id],['student_id',$student_id]])->first();
     }
    $noneAcademic=Mark::whereIn('report_id',[$report_id])->with('subjects')
    ->where([['student_id',$student_id],['type','None Academic']])->whereNotIn('total',[0])
    ->distinct('subject_id')->get();

      //
       $principal_comment=null;
       if($report->isManualPrincipalComment>0){
           $principal_comment=$this->getManualPrincipalComment($student_id,$report_id);
       }else{
       $principal_comment=$this->principalComment($summary?$summary->average_scores:0,$report->gradinggroup_id);
       }
      $staff_comment=$this->staffComment($student_id,$report_id);

        $LDomain=$this->learningDomain($student_id,$report_id);

        return response()->json(['principal_comment'=>$principal_comment,'scores'=>$scores,'summary'=>$summary,'user'=>$user,
         'pastTotal'=>$collect,'attendance'=>$attendance,
         'comment'=>$comment,'signature'=>$principal_sign, 'staff_comment'=>$staff_comment, 'crecheComment'=>$creche['crecheComment'],
         'avgReport'=>$avgReport,
         'report'=>$report,'arm'=>$arm,'gradings'=>$grading,'noneAcademic'=>$noneAcademic,'LDomain'=>$LDomain,'subjectDropt'=>$subjectDropt]);



}



public function principalComment($average,$gradinggroup_id){
    if($average){
    $comment=Comment::where([['lower_bound','<=',$average],['upper_bound','>=',$average],['school_id',auth('api')->user()->school_id]],['group_id',$gradinggroup_id])->first();
     if($comment){

    return $comment->comment;
     }
     else {
         return '-';
     }
    }
    else {
        return '-';
    }
    }




    public function getManualPrincipalComment($student_id,$report_id){

        $comment=PrincipalComent::with('comments')->where([['student_id',$student_id],['report_id',$report_id]])
        ->first();
         if($comment&&$comment->comments){
        return $comment->comments->comment;
         }
         else {
             return '';
         }
        }

public function staffComment($student_id,$report_id){

        $comment=TeachersComment::with('comments')->where([['student_id',$student_id],['report_id',$report_id]])
        ->first();
         if($comment&&$comment->comments){
        return $comment->comments->comment;
         }
         else {
             return '';
         }
        }


        public function learningDomain($student_id,$report_id){
            return Assessment::with('Ldomain')->where([['report_id',$report_id],['student_id',$student_id]])->get();
        }


        public function getCrecheComment($report,$student_id){
      $summary=null;
      $scoreArr=[];
    $domains=CrechestudentDomain::where([['report_id',$report->id],['student_id',$student_id]])->distinct('domain_id')->pluck('domain_id');
    for($i=0;$i<count($domains);++$i){

        $subdomains=  CrechestudentDomain::where([['domain_id',$domains[$i]],['report_id',$report->id],['student_id',$student_id]])->with(['ratings','subdomains'])->get();
        $domain=CrecheDomain::findOrFail($domains[$i]);
            array_push($scoreArr,['domain'=>$domain->name,'subdomains'=>$subdomains]);
    }
    $scores=collect($scoreArr);
    if($report->type==='creche'){
      $summary=CrechestudentDomain::with(['student'])->where([['report_id',$report->id],['student_id',$student_id]])->first();
    }
  return ['crecheComment'=>$scores,'summary'=> $summary];
}







 public function blueRidgeSummary($report,$student_id){

      $result=Result::where('level_id',$report->level_id)->get();
      $resultCollect=collect($result->toArray());
      $reportArr=[];
       $allReports=Report::where('level_id',$report->level_id)->get();
      foreach($allReports as $rep){

      $maxAvg= $resultCollect->where('report_id',$rep->id)
                               ->max('average_scores');
        $studentAvg=$resultCollect
        ->where('report_id',$rep->id)
        ->where('student_id',$student_id)->first()['average_scores'];
        $maxCummulativeAvg=$resultCollect->where('report_id',$rep->id)
        ->max('cummulative_average');
        $studentCummulative=$resultCollect->where('report_id',$rep->id)
        ->where('student_id',$student_id)->first()['cummulative_average'];
        $classAvg=$resultCollect->where('report_id',$rep->id)
        ->average('average_scores');
        $classCummulativeMax=$resultCollect->where('report_id',$rep->id)->first()['cummulative_average'];
        $classCummulativeAvg=$resultCollect->where('report_id',$rep->id)->average('cummulative_average');

        $summary=[
            'term_id'=>$rep->term_id,
            'student_id'=>$student_id,
            'studentAvg'=>$studentAvg,
            'classAvg'=>round($classAvg,2),
            'maxAvg'=>$maxAvg,
            'studentCummulative'=>$studentCummulative,
            'maxCummulative'=>$maxCummulativeAvg,
            'classCummulativeAvg'=>round($classCummulativeAvg),
            'classCummulativeMax'=>$classCummulativeMax
        ];


        array_push($reportArr,$summary);
      }

         return $reportArr;
 }





}

