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
use App\CheckResult;
use App\Staff_comment;
use App\TeachersComment;
use App\Result_activation;
use App\Events\MarksCreated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Exports\Mastersheet;
use Maatwebsite\Excel\Facades\Excel;
use App\Level_history;
use App\Markcheck;
use App\Imports\MarksImport;
use App\Teachersubject;
use PDF;
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


    public function pdfdownload($id,$report_id)

    { // $items = DB::table("items")->get();

        $scores= $this->studenResult($report_id,$id) ;
          view()->share([
             'school'=>$scores['school'],
             'scores'=>$scores['scores'],
             'summary'=>$scores['summary'],
             'user'=>$scores['user'],'
             pastTotal'=>$scores['pastTotal'],
               'comment'=>$scores['comment'],
               'principal_comment'=>$scores['principal_comment']?$scores['principal_comment']:'',
               'signature'=>$scores['signature'],
                'staff_comment'=>$scores['staff_comment'],
                'report'=>$scores['report'],
                'arm'=>$scores['arm'],
                'gradings'=>$scores['gradings'],
                'LDomain'=>$scores['LDomain'],
                'noneAcademic'=>$scores['noneAcademic'],
                ]);
         //return$scores['school'];
    // return view('result');


            $pdf = PDF::loadView('result');
            return $pdf->download('results.pdf');


    }



public function studenResult( $report_id, $student_id=null)
{
 // return Mark:: where([['subject_id',98],['level_id',28]])->whereNotIn('report_id',[140,141,22])->get();
    $principal_sign=User::where([['type','principal'],['school_id',auth()->user()->school_id]])->select('photo')->first();
         $school=School::findOrFail(auth()->user()->school_id);
    if($student_id===null){
       //   $student_id=auth('api')->user()->student_id;
      }

        $student=Student::findOrFail($student_id);
        $comment=Result_activation::where([['report_id',$report_id],['student_id',$student_id]])->first();
      $report=Report::with(['levels','sessions','terms'])->where('id',$report_id)->first();

    $pastTotal=Mark::select('annual_score','subject_id','term_id')
         ->where([['student_id',$student_id],['level_id',$report->level_id]
         ])->whereNotIn('report_type',['mid_term'])->distinct('term_id')->get();

          $pastTotalarray=[];
          foreach ($pastTotal as $total ) {

              array_push($pastTotalarray,['subject_id'=>$total->subject_id,'term_id'=>$total->term_id,'total'=>$total->annual_score]);
              # code...
          }

  $level_sub=Level_sub::where('level_id',$report->level_id)->pluck('subject_id');
      Mark::where('report_id',$report_id)->whereNotIn('subject_id',$level_sub)->distinct('subject_id')->delete();

     $arm=Arm::findOrFail($student->arm_id);
    // $this->resultSummary($report_id, $student_id,$student->arm_id);
    $user=User::with(['students','school'])->where('student_id',$student_id)->first();
    $grading=Grading::whereIn('group_id',[$report->gradinggroup_id])->where('school_id',$user->school_id)->get();
    $summary=Result::with(['student'])->where([['report_id',$report_id],['student_id',$student_id]])->first();
   $scores=Mark::whereNotIn('total',[0])->with('subjects')->where([['report_id',$report_id],
    ['student_id',$student_id],['type','Academic']])->distinct('subject_id')->get();
    $noneAcademic=Mark::whereNotIn('total',[0])->whereNotIn('class_avg_score',[0])->with('subjects')->where([['report_id',$report_id],
    ['student_id',$student_id],['type','None Academic']])->get();

    if($summary){
        $principal_comment=$this->principalComment($summary?$summary->average_scores:0);
       $staff_comment=$this->staffComment($student_id,$report_id);
        $LDomain=$this->learningDomain($student_id,$report_id);

        return [ 'school'=>$school,'scores'=>$scores,'summary'=>$summary,'user'=>$user,'pastTotal'=>$pastTotalarray,
    'comment'=>$comment,'principal_comment'=>$principal_comment,'signature'=>$principal_sign, 'staff_comment'=>$staff_comment,
     'report'=>$report,'arm'=>$arm,'gradings'=>$grading,'noneAcademic'=>$noneAcademic,'LDomain'=>$LDomain];
    }else{
        return ['Not_found'=>'No reusult found'];
    }

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
                $subjects=Level_sub::with('subjects')->where('level_id',$report->level_id)->get();
                $students=Student::whereIn('id',$ids)->select('id','surname','first_name','middle_name','class_id','arm_id')->get();


                $Allscores =Mark::whereIn('level_id',[$report->level_id])
                  // ->whereIn('report_type',['default-result'])
                   ->join('subjects','marks.subject_id','=','subjects.id')
                   ->select('subjects.name as subject','marks.test1 as ca1','marks.test2 as ca2','marks.exams as exam',
                   'marks.total as total','subjects.id as subject_id','marks.arm_id as arm_id','marks.student_id as student_id',
                   'marks.cummulative_avg as cumm_avg','marks.term_id as term_id')
                   ->orderBy('term_id')->orderBy('student_id')->get();
                foreach($students as $student){

                     $arm=null;
                  $scores=collect($Allscores)->whereIn('student_id',$student->id)->all();
                   $total =collect($totals)->whereIn('student_id',$student->id)->sum('total');

                   $name=$student->surname.' '.$student->first_name.' '.$student->middle_name;
                   $collect=collect(['STUDENT ID'=>$student->id,'NAMES'=>$name]);
                   $subject_headers=collect(['STUDENT ID'=>'STUDENT ID','NAMES'=>'NAMES']);

                    foreach($subjects as $subject){
                                  $isSubject=0;
                                  $average=collect($scores)->where('subject_id',$subject->subject_id)->avg('total');
                                  $subject_headers=$subject_headers->put($subject->subjects->name,$subject->subjects->name);
                                  $subject_headers=$subject_headers->put('t2'.$subject->subjects->name,'');
                                  $subject_headers=$subject_headers->put('t3'.$subject->subjects->name,'');
                                  $subject_headers=$subject_headers->put('cum'.$subject->subjects->name,'');


                                foreach($scores as $score){
                               $arm=$score->arm_id;
                                 // $isSubject=0;
                               if($score->subject_id===$subject->subject_id){

                                   $isSubject=1;
                                   $term_id=$score->term_id;
                                   switch ($term_id) {
                                           case 1:
                                          $collect=  $collect->put($subject->subjects->name.'t1',$score->total?round($score->total,2):'');
                                          break;
                                           case 2:
                                          $collect=  $collect->put($subject->subjects->name.'t2',$score->total?round($score->total,2):'');
                                          break;
                                                case 3:
                                          $collect=  $collect->put($subject->subjects->name.'t3',$score->total?round($score->total,2):'');

                                          break;
                                          default:
                                           # code...
                                           break;
                                   }







                            }


                        }

                               if($isSubject<1){


                                $collect=  $collect->put($subject->subjects->name.'t1','');
                                $collect=  $collect->put($subject->subjects->name.'t2','');
                                $collect=  $collect->put($subject->subjects->name.'t3','');
                                $collect=  $collect->put($subject->subjects->name.'cum','');



                            }else{
                                $collect=  $collect->put($subject->subjects->name.'cum',round($average,2));
                            }


                    }

                    // $arms=Arm::findOrFail($arm);
                    // $collect= $collect->put('TOTAL',round($total,2));
                    // $collect= $collect->put('CLASS ARM',$arms->name);



                      ///return $collect;
                    array_push($masterSheet, $collect->toArray());

                    //array_push($masterSheet,...$sc);
                   }  // $subject->subjects->name=>$subject_ScoreArr
                  // return count($subjects);
         $export = new Mastersheet($masterSheet,$subject_headers->toArray(),count($subjects));

    return Excel::download($export, 'Mastersheet.csv');
                 //return ['mastersheet'=>$masterSheet];
             }

}
