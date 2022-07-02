<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Arm;
use App\Has_arm;
use App\School;
use App\Mark;
use App\User;
use App\Report;
use App\Result;
use App\Comment;
use App\Grading;
use App\Student;
use App\Subject;
use App\Level_sub;
use App\Level;
use App\Assessment;
use App\Attendance;
use App\CheckResult;
use App\CrecheDomain;
use App\CrechestudentDomain;
use App\Staff_comment;
use App\TeachersComment;
use App\Result_activation;
use App\Events\MarksCreated;
use App\Exports\MasterAndCa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Exports\Mastersheet;
use Maatwebsite\Excel\Facades\Excel;
use App\Level_history;
use App\Markcheck;
use App\Imports\MarksImport;
use App\Teachersubject;
use PDF;
use App\Mail\SendMailPdf;
use App\PrincipalComent;
use Illuminate\Support\Facades\Mail;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function download($id)
    {   $user=Auth()->user();

          if($user->type==='student'){
              //return $user->student_id;
              return $this->pdfdownload($user->student_id,$id);
          }
          if($user->type==='parent'){
            $report=Report::findOrFail($id);
            $student_id=Student::where([['class_id',$report->level_id],['parent_id',$user->parent_id]])->first()->id;
          return $this->pdfdownload($student_id,$id);
    }

        $report=Report::findOrFail($id);
        $stu= Mark::where('report_id',$id)->distinct('student_id')->pluck('student_id');
        if($user->type==='tutor'){
        $arm=Has_arm::where('staff_id',$user->staff_id)->first();
        return $students=Student::with('arm')->whereIn('id',$stu)->where('arm_id',$arm->arms_id)->orderby('students.surname')->paginate(25);
        }

   $students=Student::with('arm')->whereIn('id',$stu)->orderby('students.surname')->get();
          view()->share(['students'=>$students,'report'=>$report]);
   return view('welcome');
    }


    public function pdfdownload($id,$report_id,$email=null)

    { // $items = DB::table("items")->get();
    //$school=School::findOrFail(auth()->user()->school_id);
 $scores= $this->studenResult($report_id,$id) ;
        //return $Totals=collect($scores['pastTotal']);

      // return $LDomain=$this->learningDomain($id,$report_id);
        $school=School::findOrFail(auth()->user()->school_id);
      view()->share([
             'school'=>$school,
             'scores'=>$scores['scores'],
             'summary'=>$scores['summary'],
             'user'=>$scores['user'],
              'Totals'=>collect($scores['pastTotal']),
               'comment'=>$scores['comment'],
               'principal_comment'=>$scores['principal_comment']?$scores['principal_comment']:'',
               'signature'=>$scores['signature'],
                'staff_comment'=>$scores['staff_comment'],
                'report'=>$scores['report'],
                'arm'=>$scores['arm'],
                'gradings'=>$scores['gradings'],
                'LDomain'=>$scores['LDomain'],
                'noneAcademic'=>$scores['noneAcademic'],
                'attendance'=>$scores['attendance'],
                'subjectDropt'=>$scores['subjectDropt'],
                'crecheComment'=>$scores['crecheComment'],
                ]);
         //return$scores['school'];
    // return view('result');

 $pdf = PDF::loadView('result');
$student=Student::findOrFail($id);
if($email>0)
{

   // $parentsEmail= User::where('parents_id',$student->parents_id)->first();
    $message="We are happy to send to you {$student->first_name} {$student->surname} results. Please check the attachment for details. Thanks for being our parent";
    $subject="{$student->first_name} {$student->surname} Results";

   // return $parentsEmail;
   $data = array(
            'name'   => $student->first_name,
            'message'   =>$message,
            'subject'=> $subject,
            'school'=>$scores['school']

        );
        if(!empty($student->femail)){
    Mail::to($student->femail)->send(new SendMailPdf($data,$pdf));

    return back()->withSuccess("Results Sents successfully to {$student->femail}");
}
else{
    $data['message']='No Fathers email Results found';
 Mail::to('attegift@gmail.com')->send(new SendMailPdf($data,$pdf));
    return back()->withErrors("No Fathers email Results found");
}

}
else{
return  $pdf->download($student->first_name.' '.$student->surname.'.pdf');
}
 //$pdf = PDF::loadHTML($html);
}




public function studenResult( $report_id, $student_id=null)
{
    //Mark::whereIn('report_id',[314,315,317,318,377,378,379,380,0])->delete();
    $scoreArr=[];
    $scores=null;
    $summary=null;
     $report=Report::with(['levels','sessions','terms'])->where('id',$report_id)->first();
 // return Mark:: where([['subject_id',98],['level_id',28]])->whereNotIn('report_id',[140,141,22])->get();
    $principal_sign=User::where([['type','principal'],['school_id',auth()->user()->school_id]])->select('photo')->first();
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
          // MADONNA ANNUAL
         // if($report->type==='')
    // $pastTotal=Mark::whereIn('level_id',[$report->level_id])->where('student_id',$student_id)
    //           ->whereNotIn('report_type',['mid_term','default-midterm','default-result'])
    //              ->select('annual_score','subject_id','term_id','total')->distinct('term_id')->get();

                 //Thinks School Annual
         $pastTotal=Mark::select('total as annual_score','subject_id','term_id','report_id')
         ->where([['student_id',$student_id],['level_id',$report->level_id]
         ])->whereNotIn('report_type',['mid_term','default-midterm'])->whereNotIn('term_id',[4,3])->distinct(['term_id','subject_id'])->get();



          $pastTotalarray=[];
          foreach ($pastTotal as $total ) {

              array_push($pastTotalarray,['subject_id'=>$total->subject_id,'term_id'=>$total->term_id,'total'=>$total->annual_score]);
              # code...
          }
          $collect= collect($pastTotalarray)->all();


  $level_sub=Level_sub::where('level_id',$report->level_id)->pluck('subject_id');
      Mark::where('report_id',$report_id)->whereNotIn('subject_id',$level_sub)->distinct('subject_id')->delete();
    $student=Student::findOrFail($student_id);
     $arm=Arm::findOrFail($student->arm_id);
    // $this->resultSummary($report_id, $student_id,$student->arm_id);
    $user=User::with(['students','school'])->where('student_id',$student_id)->first();
    $grading=Grading::whereIn('group_id',[$report->gradinggroup_id])->where('school_id',$user->school_id)->get();
     $subjectDropt=null;
    if($report->isCummulative){
              $subjectDropt=$this->getSubjectDroped($report,$student_id,$level_sub);
    }

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

        return ['principal_comment'=>$principal_comment,'scores'=>$scores,'summary'=>$summary,'user'=>$user,
         'pastTotal'=>$collect,'attendance'=>$attendance,
         'comment'=>$comment,'signature'=>$principal_sign, 'staff_comment'=>$staff_comment, 'crecheComment'=>$creche['crecheComment'],
         'report'=>$report,'arm'=>$arm,'gradings'=>$grading,'noneAcademic'=>$noneAcademic,'LDomain'=>$LDomain,
         'subjectDropt'=>$subjectDropt];



}


public function principalComment($average){
    $comment=Comment::where([['lower_bound','<=',$average],['upper_bound','>=',$average],['school_id',auth()->user()->school_id]])->first();
     if($comment){
    return $comment->comment;
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






          public function export_master($report_id){
                $masterSheet=[];
                $masterArr=[];
                $subject_headers=[];

                $count=0;
                $report=Report::findOrFail($report_id);
                $ids=Mark::whereIn('level_id',[$report->level_id])->distinct('student_id')->pluck('student_id');
                $totals =Mark::whereIn('report_id',[$report->level_id])->get();
                $students=Student::with('arm')->whereIn('id',$ids)->select('id','surname','first_name','middle_name','class_id','arm_id')->get();


                $Allscores =Mark::whereIn('level_id',[$report->level_id])
                         ->whereNotIn('total',[0])
                  // ->whereIn('report_type',['default-result'])
                   ->join('subjects','marks.subject_id','=','subjects.id')
                   ->select('subjects.name as subject','marks.test1 as ca1','marks.test2 as ca2','marks.exams as exam',
                   'marks.total as total','subjects.id as subject_id','marks.arm_id as arm_id','marks.student_id as student_id',
                   'marks.cummulative_avg as cumm_avg','marks.term_id as term_id')
                   ->orderBy('term_id')->orderBy('subject_id')
                   ->orderBy('student_id')->get();
                   $i=1;
                   $lSubjectId=collect($Allscores)->pluck('subject_id')->toArray();
                 $subjects=Level_sub::whereIn('subject_id',$lSubjectId)
                 ->with('subjects')->where('level_id',$report->level_id)
                   ->get();
                foreach($students as $student){

                     $arm=null;
                  $myScores=collect($Allscores)->where('student_id',$student->id);
                 //  $total =collect($totals)->whereIn('student_id',$student->id)->sum('total');

                     $name=$student->surname.' '.$student->first_name.' '.$student->middle_name;
                     $collect=collect([ 'S/N'=>$i,'STUDENT ID'=>$student->id,'NAMES'=>$name,'ARM'=>$student->arm->name]);
                     $subject_headers=collect(['SUBJECT'=>'NAMES','SUBJECT'=>' ','SUBJECT'=>' ','SUBJECT'=>' ']);
                    //$subject_headers=$subject_headers->put($collect->subjects->name,$collect->scollect->name);
                     $subject_headers=collect(['SUBJECT'=>'SUBJECT','STUDENT ID'=>'-','NAMES'=>'- ','ARM'=>'-']);
                    // $isSubject=0;
                    foreach($subjects as $subject){
                                 /// $isSubject=0;
                                  $subject_headers=$subject_headers->put($subject->subjects->name,$subject->subjects->name);
                                  $subject_headers=$subject_headers->put('t2'.$subject->subjects->name,'');
                                  $subject_headers=$subject_headers->put('t3'.$subject->subjects->name,'');
                                  $subject_headers=$subject_headers->put('cum'.$subject->subjects->name,'');


                                   $scores=$myScores->where('subject_id',$subject->subject_id)->values();
                                   if(count($scores)>0){
                                   $term1=count(collect($scores)->where('term_id',1)->values())>0?
                                           collect($scores)->where('term_id',1)->values()[0]->total:' ';
                                   $term2=count(collect($scores)->where('term_id',2)->values())>0?
                                          collect($scores)->where('term_id',2)->values()[0]->total:' ';
                                    $term3=count(collect($scores)->where('term_id',3)->values())>0?
                                          collect($scores)->where('term_id',3)->values()[0]->total:' ';
                                    $average=collect($scores)->avg('total');

                                   $collect=  $collect->put($subject->subjects->name.'t1',$term1);
                                   $collect=  $collect->put($subject->subjects->name.'t2',$term2);
                                   $collect=  $collect->put($subject->subjects->name.'t3',$term3);
                                   $collect=  $collect->put($subject->subjects->name.'cum',$average?round($average,2):' ');
                                   }else{

                                    $collect=  $collect->put($subject->subjects->name.'t1',' ');
                                   $collect=  $collect->put($subject->subjects->name.'t2',' ');
                                   $collect=  $collect->put($subject->subjects->name.'t3',' ');
                                   $collect=  $collect->put($subject->subjects->name.'cum',' ');
                                   }

                                 //return$collect;
                                 $term1=null;
                                 $term2=null;
                                 $term3=null;
                                 $average=null;

                                }

                               // return$collect;
                                        array_push($masterSheet, $collect->toArray());
                            }
         $export = new Mastersheet($masterSheet,$subject_headers->toArray(),count($subjects));

         return Excel::download($export, 'Mastersheet.csv');
                 //return ['mastersheet'=>$masterSheet];
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


  public function getSubjectDroped($report,$student_id,$level_sub){

          $allMarks=Mark::with('subjects')
                      ->whereIn('subject_id',$level_sub)
                      ->whereIn('student_id',[$student_id]);
                      $currentSubjects=$allMarks->whereIn('report_id',[$report->id])->pluck('subject_id');
                        $subjectDropt=Mark::whereNotIn('report_id',[$report->id])
                                       ->whereNotIn('subject_id',$currentSubjects)
                                       ->whereNotIn('total',[0])
                                       ->where([['student_id',$student_id],['level_id',$report->level_id]])
                                      ->with('subjects')
                                     ->select('subject_id','total','cummulative_avg')
                                      //->sum('total');
                                      ->get();
                                      if(!empty($subjectDropt)){
                                          $subjectDroptArr=[];
                                         $subjectIDs=collect($subjectDropt)->unique('subject_id')->all();
                                          foreach($subjectIDs as $ubjectId){
                                             $average=collect($subjectDropt)->where('subject_id',$ubjectId['subject_id'])->avg('total');
                                           $grandTotal=collect($subjectDropt)->where('subject_id',$ubjectId['subject_id'])->sum('total');
                                             array_push($subjectDroptArr,['subject'=>$ubjectId->subjects->name,'average'=>$average,'grandTotal'=>$grandTotal]);

                                          }

                                       $subjectDropt=$subjectDroptArr;
                                      }
                         return $subjectDropt;
                      }






                        public function masterAndCa($report_id){
                $masterSheet=[];
                $masterArr=[];
                $subject_headers=[];

                $count=0;
                $report=Report::findOrFail($report_id);
                $ids=Mark::whereIn('report_id',[$report_id])->distinct('student_id')->pluck('student_id');
                //$totals =Mark::whereIn('report_id',[$report->level_id])->get();
                $students=Student::with('arm')->whereIn('id',$ids)->select('id','surname','first_name','middle_name','class_id','arm_id')->get();


                $Allscores =Mark::whereIn('report_id',[$report_id])
                 ->whereNotNull('total')
                  // ->whereIn('report_type',['default-result'])
                   ->join('subjects','marks.subject_id','=','subjects.id')
                   ->select('subjects.name as subject','marks.test1 as ca1','marks.test2 as ca2','marks.exams as exam',
                   'marks.total as total','subjects.id as subject_id','marks.arm_id as arm_id','marks.student_id as student_id',
                   'marks.cummulative_avg as cumm_avg','marks.term_id as term_id')
                   ->orderBy('term_id')->orderBy('subject_id')
                   ->orderBy('student_id')->get();
                   $i=1;
                  // $lSubjectId=collect($Allscores)->pluck('subject_id')->toArray();
                 $subjects=Level_sub::where('level_id',$report->level_id)
                 ->with('subjects')->where('level_id',$report->level_id)
                   ->get();
                foreach($students as $student){

                    // $arm=null;
                  $scores=collect($Allscores)->where('student_id',$student->id);
                 //  $total =collect($totals)->whereIn('student_id',$student->id)->sum('total');

                   $name=$student->surname.' '.$student->first_name.' '.$student->middle_name;
                   $collect=collect([ 'S/N'=>$i,'STUDENT ID'=>$student->id,'NAMES'=>$name,'ARM'=>$student->arm->name]);
                   $subject_headers=collect(['SUBJECT'=>'NAMES','SUBJECT'=>' ','SUBJECT'=>' ','SUBJECT'=>' ']);
                    //$subject_headers=$subject_headers->put($collect->subjects->name,$collect->scollect->name);
                  $subject_headers=collect(['SUBJECT'=>'SUBJECT','STUDENT ID'=>'-','NAMES'=>'- ','ARM'=>'-']);

                    foreach($subjects as $subject){
                                  $isSubject=0;
                                  $subject_headers=$subject_headers->put($subject->subjects->name,$subject->subjects->name);
                                  $subject_headers=$subject_headers->put('t2'.$subject->subjects->name,'');
                                 // $subject_headers=$subject_headers->put('t3'.$subject->subjects->name,'');
                                  $subject_headers=$subject_headers->put('total'.$subject->subjects->name,'');


                                foreach($scores as $score){
                             //  $arm=$score->arm_id;
                                 // $isSubject=0;
                               if($score->subject_id==$subject->subject_id){

                                   $isSubject=1;

                                   $collect=  $collect->put($subject->subjects->name.'ca1',$score->ca1?round($score->ca1,2):' ');
                                   $collect=  $collect->put($subject->subjects->name.'ca2',$score->ca1?round($score->ca2,2):' ');
                                   //$collect=  $collect->put($subject->subjects->name.'ca3',$score->ca1?round($score->ca3,2):' ');
                                   $collect=  $collect->put($subject->subjects->name.'exam',$score->exam?round($score->exam,2):' ');
                            }


                        }

                               if($isSubject==0){


                                   $collect=  $collect->put($subject->subjects->name.'ca1',$score->eca1?round($score->ca1,2):' ');
                                   $collect=  $collect->put($subject->subjects->name.'ca2',$score->eca1?round($score->ca2,2):' ');
                                   //$collect=  $collect->put($subject->subjects->name.'ca3',$score->eca1?round($score->ca3,2):' ');
                                   $collect=  $collect->put($subject->subjects->name.'exam',$score->exame?round($score->exam,2):' ');




                            }
                                //  $average=collect($scores)->where('subject_id',$subject->subject_id)->avg('total');
                                // $collect=  $collect->put($subject->subjects->name.'cum',$average?round($average,2):'-');



                    }
//return $collect;
                    // $arms=Arm::findOrFail($arm);
                    // $collect= $collect->put('TOTAL',round($total,2));
                    // $collect= $collect->put('CLASS ARM',$arms->name);



                      ///return $collect;
                    array_push($masterSheet, $collect->toArray());
$i=$i+1;
//return $masterSheet;
                    //array_push($masterSheet,...$sc);
                   }  // $subject->subjects->name=>$subject_ScoreArr
                  // return count($subjects);
         $export = new MasterAndCa($masterSheet,$subject_headers->toArray(),count($subjects));

    return Excel::download($export, 'Scores-sheet.csv');
                 //return ['mastersheet'=>$masterSheet];
             }


  }


