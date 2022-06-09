<?php

namespace App\Http\Controllers\API;
use App\Report;
use App\Term;
use App\Sessions;
use App\Result;
use App\Student;
use App\Mark;
use App\Exports\Mastersheet;
use App\User;
use App\Arm;
use App\Assessment;
use Illuminate\Support\Facades\DB;
use App\Staff_comment;
use App\Comment;
use App\Grading;
use App\Has_arm;
use App\CrechestudentDomain;
use App\Level_history;
use App\Result_activation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\API\ScoreController as Gradding;
use App\Level;
use App\Level_sub;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        //
        $user=auth('api')->user();
        if($user->type==='student'){
      $combinedLevel=[];
     $history=Mark::where('student_id',$user->student_id)->distinct('level_id')->pluck('level_id')->toArray();
     $currentLevel=Student::where('id',$user->student_id)->pluck('class_id');
if(count($history)>0){
  $combinedLevel=$history;
}

 //$combinedLevel= $currentLevel;

     array_push($combinedLevel, $currentLevel[0]);

     return Report::whereIn('level_id',$combinedLevel)
                  ->with('levels')
                  ->leftJoin('result_activations',function($join) use($user){
                      $join->on('result_activations.report_id','=','reports.id')
                      ->where([['result_activations.student_id',$user->student_id]]);
                  })

         ->select(DB::raw('reports.*, result_activations.activation_status as activation_status'))
         ->distinct('reports.id')  ->orderby('id','desc')->paginate(10);

        }
if($user->type==='tutor'){
  $historyLevel=Level::where('is_history',1)->pluck('id');
              // $arm=Has_arm::where(['staff_id',$user->staff_id])->whereNotIn('level_id',$historyLevel)->first();
            $arm=Has_arm::where('staff_id',$user->staff_id)->whereNotIn('level_id',$historyLevel)->first();
    return Report::with(['levels','terms'])->where([['school_id',$user->school_id],['level_id',$arm->level_id]])->latest()->paginate(10);
}

if($user->type==="parent"){
$siblings=Student::whereIn('parent_id',[$user->parent_id]);
$siblingsIlevelIds=$siblings->pluck('class_id')->toArray();
$siblingsIDs=$siblings->pluck('id');
$historyLevelIds=Level_history::whereIn('student_id',$siblingsIDs)->distinct('level_id')->pluck('level_id')->toArray();
$totalLevelIds=array_merge($siblingsIlevelIds,$historyLevelIds);
return Report::whereIn('level_id',$totalLevelIds)
                  ->with('levels')
                  ->leftJoin('result_activations',function($join) use($siblingsIDs){
                      $join->on('result_activations.report_id','=','reports.id')
                      ->whereIn('result_activations.student_id',$siblingsIDs);
                  })
             ->select(DB::raw('reports.*, result_activations.activation_status as activation_status'))
             ->distinct('reports.id')  ->orderby('id','desc')->paginate(10);


                }
return Report::with(['levels','terms'])->where('school_id',$user->school_id)->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'level_id' => 'required|integer',
            'term_id' => 'required|integer',
            'session_id' => 'required|integer',
            'school_id'=>'required|integer',
            'gradinggroup_id'=>'required|integer',

        ]);
       // return $request->all();
        return Report::create($request->all());
    }



    public function edit($id)
    {
        return Report::findOrFail($id);
    }


    public function update(Request $request)
    {
        //
        $request->all();
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'level_id' => 'required|integer',
            'term_id' => 'required|integer',
            'session_id' => 'required|integer',
            'gradinggroup_id'=>'required|integer',

        ]);
        $report=Report::findOrFail($request->id);
        $report->update($request->all());
        return ['message'=>'report updated successfully'];
    }


    public function destroy($id)
    {
        //
        $report=Report::findOrFail($id);
        $report->delete();
        Mark::whereIn('report_id',[$id])->delete();
        return ['message'=>'report deleted successfully'];
    }

    public function session_list()
    {
        return Sessions::where('school_id',auth('api')->user()->school_id)->get();
        # code...
    }
    public function term_list()
    {
        return Term::all();
        # code...
    }

   public function student_list($id)
   {
    $stu=null;
    $user=auth('api')->user();
       $report=Report::findOrFail($id);
       if($report->type==='creche'){
        $stu= CrechestudentDomain::where('report_id',$id)->distinct('student_id')->pluck('student_id');
       }else{
        $stu= Mark::where('report_id',$id)->distinct('student_id')->pluck('student_id');
       }

       if($user->type==='tutor'){
       $arm=Has_arm::where('staff_id',$user->staff_id)->first();
       return $students=Student::with('arm')->whereIn('id',$stu)->where('arm_id',$arm->arms_id)->orderby('students.surname')->paginate(50);
       }

     return $students=Student::with('arm')->whereIn('id',$stu)->orderby('students.surname')->paginate(50);
     //return response()->json(['students'=>$students,'report'=>$report]);
   }

public function studenResult( $report_id, $student_id=null)
{

    $principal_sign=User::where([['type','principal'],['school_id',auth('api')->user()->school_id]])->select('photo')->first();
      if($student_id===null){
           $student_id=auth('api')->user()->student_id;
      }
        $student=Student::findOrFail($student_id);
        $comment=Result_activation::where([['report_id',$report_id],['student_id',$student_id]])->first();
       $report=Report::with(['levels','sessions','terms'])->where('id',$report_id)->first();
        $pastTotal=Mark::select('total','subject_id','term_id')
         ->where([['student_id',$student_id],['level_id',$report->level_id],
         ])->orderBy('id','asc')->get();
           $pastTotalarray=[];
           foreach ($pastTotal as $total ) {
               array_push($pastTotalarray,['subject_id'=>$total->subject_id,'term_id'=>$total->term_id,'total'=>$total->total]);
               # code...
           }


    $arm=Arm::findOrFail($student->arm_id);
    $user=User::with(['students','school'])->where('student_id',$student_id)->first();
    $grading=Grading::where('school_id',$user->school_id)->get();
    $summary=Result::with(['student'])->where([['report_id',$report_id],['student_id',$student_id]])->first();
    $scores=Mark::whereNotIn('total',[0])->with('subjects')->where([['report_id',$report_id],
    ['student_id',$student_id],['type','Academic']])->get();
    $noneAcademic=Mark::with('subjects')->where([['report_id',$report_id],
    ['student_id',$student_id],['type','None Academic']])->get();
    if(count($scores)>0){
        $principal_comment=$this->principalComment($summary->average_scores);
        $staff_comment=$this->staffComment($summary->average_scores);
        $LDomain=$this->learningDomain($student_id,$report_id);
        return response()->json(['scores'=>$scores,'summary'=>$summary,'user'=>$user,'pastTotal'=>$pastTotalarray,
    'comment'=>$comment,'principal_comment'=>$principal_comment,'signature'=>$principal_sign, 'staff_comment'=>$staff_comment,
     'report'=>$report,'arm'=>$arm,'gradings'=>$grading,'noneAcademic'=>$noneAcademic,'LDomain'=>$LDomain]);
    }else{
        return ['Not_found'=>'No reusult found'];
    }

}

public function principalComment($average){
$comment=Comment::where([['lower_bound','<=',$average],['upper_bound','>=',$average],['school_id',auth('api')->user()->school_id]])->first();
 if($comment){
return $comment->comment;
 }
 else {
     return '';
 }
}




public function staffComment($average){
    $comment=Staff_comment::where([['lower_bound','<=',$average],['upper_bound','>=',$average],['school_id',auth('api')->user()->school_id]])->first();
     if($comment){
    return $comment->comment;
     }
     else {
         return '';
     }
    }

    public function learningDomain($student_id,$report_id){
        return Assessment::with('Ldomain')->where([['report_id',$report_id],['student_id',$student_id]])->get();
    }



 public function getArms($id) {
     //if(auth('api')->user()->type==='tutor'){
//       $historyLevel=Level::where('is_history',1)->pluck('id');
//     return Has_arm::with('arms')->where([['staff_id',auth('api')->user()->staff_id]])->whereNotIn('level_id',$historyLevel)->get();

//}
    $report=Report::findOrFail($id);
    return Has_arm::with('arms')->where([['level_id',$report->level_id]])->get();

}


public function masterCard($report_id,$arm_id=null){
    $report=Report::with(['sessions','terms','levels'])->where('id',$report_id)->first();
    $subjectsIds=Mark::where('report_id',$report_id)->distinct('subject_id')->pluck('subject_id');
    $subjects=Level_sub::where('level_id',$report->level_id)->whereIn('subject_id',$subjectsIds)->with('subjects')->get();
      $students=null;
      $ids=null;
    if(auth('api')->user()->type==='tutor'){
        $arm=Has_arm::where('staff_id',auth('api')->user()->staff_id)->first();
        $students=Student::where([['class_id',$report->level_id],['arm_id',$arm_id]])->get();
    }else{
               $ids=$arm_id?
                   Mark::whereIn('report_id',[$report_id])->where('arm_id',$arm_id)->pluck('student_id')->toArray()
                  :Mark::whereIn('report_id',[$report_id])->pluck('student_id')->toArray();

        //$ids=Mark::where('report_id',$report_id)->distinct('student_id')->pluck('student_id');
    }
 $ids=Mark::whereIn('report_id',[$report_id])->where('arm_id',$arm_id)->pluck('student_id')->toArray();
 $students=Student::whereIn('id',$ids)->get();
 $marks=Mark::where('report_id',$report_id)
 ->whereIn('student_id',$ids)
 ->whereNotIn('total',[0])
->orderBy('total','desc')->get();
 //positions
$results=Result::where('report_id',$report_id)->get();
    return ['subjects'=>$subjects,'report'=>$report,'students'=>$students,'marks'=>$marks,'results'=>$results];
}


public function transcript($student_id=null)
{           $scores=[];
            $transcript=[];
            $firstTerm_total=null;
            $secondTerm_total=null;
            $thirdTerm_total=null;
            $level_total=0;
            $level_averag=0;
        if(empty($student_id)){
        $student_id=auth('api')->user()->student_id;
    }
    $student=User::with(['students','school'])->where('student_id',$student_id)->first();
          $level_ids=Mark::where('student_id',$student_id)->select('level_id')->distinct('level_id')->get();

          foreach($level_ids as $level_id){
            $report=Report::with('terms','sessions','levels')->where('level_id',$level_id->level_id)->first();
              $subjects=Mark::with('subjects')->where([['student_id',$student_id],['level_id',$level_id->level_id]])->select('subject_id')->distinct('subject_id')->get();
               foreach($subjects as $subject){
               $firstTerm_total=Mark::with('subjects')->whereNotIn('total',[0])->where([['student_id',$student_id],['level_id',$level_id->level_id],['subject_id',$subject->subject_id],['term_id',1]])
               ->select('total','grade')->orderBy('term_id')->first();
               $secondTerm_total=Mark::with('subjects')->whereNotIn('total',[0])->where([['student_id',$student_id],['level_id',$level_id->level_id],['subject_id',$subject->subject_id],['term_id',2]])
               ->select('total','grade')->orderBy('term_id')->first();
                $thirdTerm_total=Mark::with('subjects')->whereNotIn('total',[0])->where([['student_id',$student_id],['level_id',$level_id->level_id],['subject_id',$subject->subject_id],['term_id',3]])
               ->select('total','grade')->orderBy('term_id')->first();
              $average_score=Mark::where([['student_id',$student_id],['level_id',$level_id->level_id],['subject_id',$subject->subject_id]])
               ->avg('total');
               $c_score=Mark::where([['student_id',$student_id],['level_id',$level_id->level_id],['subject_id',$subject->subject_id]])
               ->sum('total');
                  $grade=$this->grade($average_score,$report->gradinggroup_id);
                  $level_total=round($level_total+$c_score,2);
                      if($average_score>0){
                  array_push($scores,['subject'=>$subject->subjects->name,
               'first_term'=>$firstTerm_total?$firstTerm_total->total:'-',
               'second_term'=>$secondTerm_total?$secondTerm_total->total:'-',
               'third_term'=>$thirdTerm_total?$thirdTerm_total->total:'-',
               'total'=>$c_score,
               'average'=>round($average_score,2),
               'grade'=>$grade['grade']
               ]);

            }}
                     $level_averag=round(($level_total/count($subjects)),2);

                        array_push($transcript,['scores'=>$scores,'report'=>$report,'level_total'=>$level_total,'level_average'=>$level_averag]);
                        $scores=[];
                    }
                    $principal=User::with('employees')->where([['type','principal'],['school_id',auth('api')->user()->school_id]])->first();
                    return ['student'=>$student,'levels'=>$level_ids,'transcript'=>$transcript,'principal'=>$principal];
           }






            public function grade($score,$gradinggroup_id)
            {
                if(is_numeric($score )&& ($score>0)){
                    $score=round($score,2);
                $grading=Grading::whereIn('group_id',[$gradinggroup_id])->where([['lower_bound','<=',$score],['upper_bound','>=',$score],['school_id',auth('api')->user()->school_id]])->first();
                if($grading){
               $Grade=$grading->grade;
               $credite_point=$grading->credit_point;
               $narration=$grading->narration;

              return ["grade"=>$Grade,"narration"=>$narration,'credit_point'=>$credite_point,'total'=>$score];
            }}
            return ["grade"=>'-',"narration"=>'','credit_point'=>0,'total'=>0];
            }



            public function export_master($report_id,$arm_id=null){
                $masterSheet=[];

                $count=0;
                $student=null;
              $arms=collect(Arm::where('school_id',auth('api')->user()->school_id)->get());
                $report=Report::findOrFail($report_id);
                  $marks=$arm_id?
                   Mark::whereIn('report_id',[$report_id])->where('arm_id',$arm_id)->get()
                  :Mark::whereIn('report_id',[$report_id])->get();
                $ids=collect($marks)->whereIn('report_id',[$report_id])->unique('student_id')->pluck('student_id');

                $totals =collect($marks)->whereIn('report_id',[$report_id])->all();
                $subjects=Level_sub::with('subjects')->where('level_id',$report->level_id)->get();

                $students=Student::whereIn('id',$ids)->select('id','surname','first_name','middle_name','class_id','arm_id')->get();


                $Allscores =Mark::whereIn('report_id',[$report_id])
                  // ->whereIn('student_id',[$student->id])
                   ->join('subjects','marks.subject_id','=','subjects.id')
                   ->select('subjects.name as subject','marks.test1 as ca1','marks.test2 as ca2','marks.exams as exam',
                   'marks.total as total','subjects.id as subject_id','marks.arm_id as arm_id','marks.student_id as student_id')
                   ->get();
                foreach($students as $student){
                     $arm=null;
                   $scores=collect($Allscores)->whereIn('student_id',[$student->id])->all();
                   $total =collect($totals)->whereIn('student_id',$student->id)->whereNotIn('type',['None Academic'])->sum('total');
                   $avg =collect($totals)->whereIn('student_id',$student->id)->whereNotIn('type',['None Academic'])->avg('total');
                   $name=$student->surname.' '.$student->first_name.' '.$student->middle_name;
                   $collect=collect(['STUDENT ID'=>$student->id,'NAMES'=>$name]);
                  // $subject_headers=collect(['STUDENT ID'=>'STUDENT ID','NAMES'=>'NAMES']);

                    foreach($subjects as $subject){
                       $isSubject=0;
                        foreach($scores as $score){
                             $arm=$score->arm_id;
                               if($score->subject_id===$subject->subject_id){
                                $collect=  $collect->put(strval($subject->subjects->name),($score->total)>0?$score->total:'');
                               


                                  $isSubject=1;
                                //  $subject_headers=$subject_headers->put($subject->subjects->name,$subject->subjects->name);
                            }

                        }
                               if($isSubject<1){
                                $collect=  $collect->put(strval($subject->subjects->name),'');
                                 //$subject_headers=$subject_headers->put($subject->subjects->name,$subject->subjects->name);
                            }

                    }

                    $thisArms=$arms->where('id',$arm)->first();
                    $collect= $collect->put('TOTAL',round($total,2));
                    $collect= $collect->put('AVERAGE',round($avg,2));
                    $collect= $collect->put('CLASS ARM',$thisArms->name);





                    array_push($masterSheet, $collect->all());
                    //array_push($masterSheet,...$sc);
                   }  // $subject->subjects->name=>$subject_ScoreArr


                 return ['mastersheet'=>$masterSheet];

                }


             function transposeData($data)
{
  $retData = array();

    foreach ($data as $row => $columns) {
      foreach ($columns as $row2 => $column2) {
          $retData[$row2][$row] = $column2;
      }
    }
  return $retData;
}


public function activate_report( $id )
{
   $report=Report::findOrFail($id);
   if($report->is_ready<1){
    $report->is_ready=1;
   }
   else{
    $report->is_ready=0;
   }


   $report->save();
}




public function checkReport($id){
    return Report::findOrFail($id);
}



public function masterSheetDownload($reportId,$arm=null){

     $report=Report::with(['sessions','terms','levels'])
              ->where('id',$reportId)
              ->first();

             $subjects=Level_sub::with('subjects')
                        ->whereIn('level_id',[$report->level_id])
                        ->get();
                  $subjectIds= collect($subjects)->pluck('subject_id')  ;
                    $scores=DB::table('marks')->whereIn('report_id',[$reportId])
                         ->whereIn('subject_id',$subjectIds)
                         ->select('student_id','subject_id','total')
                         //->groupBy('student_id')
                         ->get()     ;

                       return  $scores2=collect($scores)->groupBy('student_id')
                            ->avg('total');
                           // ->all();
}

}









