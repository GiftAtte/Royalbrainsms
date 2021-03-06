<?php

namespace App\Http\Controllers\API;

use App\Arm;
use App\Has_arm;
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
use App\Result_activation;
use App\Events\MarksCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Level_history;
use App\Markcheck;
use App\Imports\MarksImport;
use App\Teachersubject;
use Maatwebsite\Excel\Facades\Excel;
ini_set('max_execution_time', '1000');
class PrimaryController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:api');
    }
     public $terminal_exam;

    public function index()
    {  $user=auth('api')->user();
        if($user->type==='tutor'){
              $historyLevel=Level::where('is_history',1)->pluck('id');
              // $arm=Has_arm::where(['staff_id',$user->staff_id])->whereNotIn('level_id',$historyLevel)->first();
            $arm=Has_arm::where('staff_id',$user->staff_id)->whereNotIn('level_id',$historyLevel)->first();
            return Report::where([['school_id',$user->school_id],['level_id',$arm->level_id]])->latest()->get();
        }
       return Report::where('school_id',auth('api')->user()->school_id)->latest()->get();
    }


    //weightrd Scores
     public function convertScores($midterm=0,$score_sum,$report_type,$exam){
              if($report_type==='primary-midterm'){
                $this->terminal_exam=$exam;
                return round($score_sum,2);
              }else{
                $this->terminal_exam=(($score_sum/100)*40);
                $total=(($midterm/100)*60) + $this->terminal_exam;
                return round($total,2);
              }


       // return round($total,2);
     }
     //Sum

    public function totalSum($t1=0,$t2=0,$exams=0){

           $sum=$t1+$t2+$exams;

        return round($sum,2);
    }



    public function store(Request $request)
    {
       $Nstudents= $request->number_of_students;
    // $students=$request->student_id;
         //$weighted_score=null;

         $midterm_score=null;
         $midterm_convert=0;
            $report=Report::findOrFail($request->report_id);

            $subject_type=Level_sub::where([['level_id',$report->level_id],['subject_id',$request->subject_id]])->first();
            $term_id=$report->term_id;

            //return  $student_id;
            for($i=0;$i<$Nstudents; ++$i){
         // $student_arm=Student::findOrFail($request->student_id[$i]);

             if($report->type==='primary-terminal'){
          $midterm_score=Mark::where([['student_id',$request->student_id[$i]],['subject_id',$request->subject_id],['term_id',$report->term_id],['session_id',$report->session_id],['report_type','primary-midterm']])->first();
          $midterm_convert=($midterm_score->total/100)*60;
        }
          //return$midterm_score;
          $score_sum=$this->totalSum($request->test1[$i],$request->test2[$i],$request->exams[$i]);

          // computing sum and grading
           $total=$this->convertScores($midterm_score?$midterm_score->total:0,$score_sum,$report->type,$request->exams[$i]);
         //  $annual_score= $midterm_score?$this->midtermSum($midterm_score->total,$report->type):0+($request->exams[$i]/100)*40
                $gradding= $this->grade($total,$report->gradinggroup_id,auth('api')->user()->school_id);
                //$annual_gradding= $this->grade($annual_score,$report->gradinggroup_id,auth('api')->user()->school_id);
            // return $this->terminal_exam;

  // return $this->studentPosition($request->student_id[$i+2],$request->report_id,false);

  Mark::where([['report_id',$request->report_id], ['student_id', $request->student_id[$i]],['subject_id',$request->subject_id]])->delete();
         Mark::create(
               [ 'student_id'=>$request->student_id[$i],
                'report_id'=>$request->report_id,
                'level_id'=>$report->level_id,
                'subject_id'=>$request->subject_id,
                      'test1'=>$request->test1[$i],
                      'test2'=>$request->test2[$i],
                      //'test3'=>$request->test3[$i],
                      //'note'=>$request->note[$i],
                      //'weighted_score'=>$total,
                      'exams'=>$this->terminal_exam,
                      'total'=>$total,
                      'grade'=>$gradding['grade'],
                      'narration'=>$gradding['narration'],
                      'term_id'=>$term_id,
                      'arm_id'=>$request->arm_id[$i],
                      'type'=>$subject_type->type,
                      'annual_score'=>$total,
                      'report_type'=>$report->type,
                      'mid_term'=>round($midterm_convert,2),
                      'session_id'=>$report->session_id,
                      'is_history'=>0
            ]
           );
         //   Mark::insert($scores);


          }

    Markcheck::create([
     'report_id'=>$request->report_id,
     'subject_id'=>$request->subject_id,
     'school_id'=>auth('api')->user()->school_id,
 ]);

 CheckResult::create([
  'report_id'=>$request->report_id,
  'is_history'=>0,
    'school_id'=>auth('api')->user()->school_id,
    'compute_summary'=>1
 ]);
 $this->terminal_exam=0;
    return ['message'=>'success'];
    }

// import scores

public function import(Request $request)
{

  $students=$request->csv;

        $subject_id=null;
         $report_id=null;
         $is_history=false;
         $check_history=intval($request->csv[0]['is_history']);
         if($check_history>0){
             $is_history=true;
         }


        //return  $student_id;
        foreach($students as $student){
         // return $student;
         $subject_id=$student['subject_id'];
          $report_id=$student['report_id'];
          $report=Report::findOrFail($report_id);
          $level_id=$report->level_id;
          $term_id=$report->term_id;
          $subject_type=Level_sub::where([['level_id',$level_id],['subject_id',$student['subject_id']]])->first();

        // computing sum and grading
      //return[$student['test1'],$student['test2'],$student['exams']];
        $total=$this->sum($student['test1'],$student['test2'],['test3'],$student['exams']);
         $gradding=$this->grade($total,$report->gradinggroup_id,auth('api')->user()->school_id);

            return $gradding;
                $student_arm=null;
                if($is_history>0){

               $student_arm=Level_history::where([['student_id',$student['student_id']],['level_id',$report->level_id]])->first();
                }else{

         $student_arm=Student::findOrFail($student['student_id']);
                }
                       Mark::where([['report_id',$report_id], ['student_id', $student['student_id']],['subject_id',$subject_id]])->delete();

         Mark::create(
            [


            'student_id'=>$student['student_id'],
            'report_id'=>$report_id,
            'level_id'=>$report->level_id,
            'subject_id'=>$subject_id,
                  'test1'=>$student['test1'],
                  'test2'=>$student['test2'],
                  'test3'=>$student['test3'],
                  'exams'=>$student['exams'],
                  'total'=>$total,
                  'grade'=>$gradding['grade'],
                  'narration'=>$gradding['narration'],
                   'arm_id'   =>$student_arm->arm_id,
                   'term_id' =>$term_id,
                   'credit_point'=>$gradding['credit_point'],
                   'type'=>$subject_type->type


        ]);



    }
   // updating the marks table with subject positioning
   Markcheck::create([
       'report_id'=>$report_id,
       'subject_id'=>$subject_id,
       'school_id'=>auth('api')->user()->school_id


   ]);

   CheckResult::create([
    'report_id'=>$report_id,
    'is_history'=>$is_history,
      'school_id'=>auth('api')->user()->school_id

]);

    //event(new MarksCreated($report_id,$students,$level_id,$subject_id));
//     $max_score=DB::table('marks')->where([['report_id',$report_id],['subject_id',$subject_id]])->max('total');
//     $min_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$report_id],['subject_id',$subject_id]])->min('total');


//     $class_avg_score=DB::table('marks')->where([['report_id',$report_id],['subject_id',$subject_id]])->avg('total');

//     // positioning
//     $class_sub_position='';
//   foreach($students as $student){

//  $cummulative_avg=DB::table('marks')->whereNotIn('total',[0])->where([['level_id',$level_id],['subject_id',$subject_id],['student_id',$student['student_id']]])->avg('total');
//  $grand_total=DB::table('marks')->where([['level_id',$level_id],['subject_id',$subject_id],['student_id',$student['student_id']]])->sum('total');
//  $subject_positions=$this->getSubjectRank($student['student_id'],$report_id,$subject_id);
//  $subject_position_arm=$this->getSubjectRank($student['student_id'],$report_id,$subject_id,$student['arm_id']);
//  $arm_max_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$subject_id],['report_id',$report_id],['arm_id',$student['arm_id']]])->max('total');
//  $arm_min_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$subject_id],['report_id',$report_id],['arm_id',$student['arm_id']]])->min('total');
//  $arm_avg_score=DB::table('marks')->whereNotIn('total',[0])->where([['subject_id',$subject_id],['report_id',$report_id],['arm_id',$student['arm_id']]])->avg('total');
//     $class_sub_position=$subject_positions['position'];
//     $arm_sub_position= $subject_position_arm['position'];

//     DB::table('marks')
//     ->where([['report_id', $report_id],['student_id',$student['student_id']],['subject_id',$subject_id]])
//     ->updateOrInsert(
//       ['report_id' => $report_id, 'student_id' => $student['student_id']],
//       ['class_avg_score' =>round($class_avg_score,2),
//                 'class_subj_position'=>$class_sub_position,
//                 'max_class_score'=>$max_score,
//                 'min_class_score'=>$min_score,
//                          'average'=>round($cummulative_avg,2),
//                          'grand_total'=>round($grand_total,2),
//                          'arm_avg_score'=>round($arm_avg_score,2),
//                          'arm_min_score'=>round($arm_min_score,2),
//                          'arm_max_score'=>round($arm_max_score,2)


//     ]
//   );

//   $mark=Mark::where([['subject_id',$subject_id],['report_id',$report_id],['student_id',$student['student_id']]])->first();
//   $mark->arm_subj_position=$arm_sub_position;
//   $mark->save();
//}






return ['message'=>'success'];
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
}

}


    // results summary

    public function resultSummary($report_id,$student_id,$arm_id,$school_id)
    {
        Result::where([['report_id',$report_id],['student_id',$student_id]])->delete();
      $report=Report::findOrFail($report_id);

      $students=Mark::whereNotIn('total',[0])->where([['report_id',$report_id],['arm_id',$arm_id]])->select('student_id')->distinct('student_id')->get();
    //    $students=Mark::where('report_id',$report_id)->whereNotIn('total',[0])
    //                   ->select('student_id')->distinct('student_id')->get();

      //$student_by_arm=Student::where
      $level_id=$report->level_id;
      $total_student=count($students);
    // return $level_id;

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
                                'arm_position'=>$this->studentPosition($student_id,$report_id,true),
                       ]
                    );
}






// Gradding



public function grade($score,$gradinggroup_id,$school_id)
{
   // $user_school=auth('api')->user()->school_id;
    if(is_numeric($score)){
        $score=round($score,2);
    $grading=Grading::whereIn('group_id',[$gradinggroup_id])->where([['lower_bound','<=',$score],['upper_bound','>=',$score],['school_id',$school_id]])->first();
    if($grading){
   $Grade=$grading->grade;
   $credite_point=$grading->credit_point;
   $narration=$grading->narration;

  return ["grade"=>$Grade,"narration"=>$narration,'credit_point'=>$credite_point,'total'=>$score];
}}
return ["grade"=>'F',"narration"=>'','credit_point'=>0,'total'=>0];
}




    public function loadStudents(Request $request)
    {
             $historyLevel=Level::where('is_history',1)->pluck('id');
         $arm=Has_arm::whereNotIn('level_id',$historyLevel)->where('staff_id',auth('api')->user()->staff_id)->first();

        $report_id=$request->report_id;
        $subject_id=$request->subject_id;
        $report=Report::findOrFail($request->report_id);
          if(auth('api')->user()->type==='tutor'){
      return  DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$arm->arms_id]])
        ->leftJoin('marks', function($join) use($report_id,$subject_id)
        {
            $join->on('marks.student_id', '=', 'students.id')
            ->where([['marks.report_id',$report_id],
                     ['marks.subject_id',$subject_id]]);
        })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
         students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
         marks.exams as exams,marks.subject_id as subject_id'))->orderBy('name')
        ->get();

    }else{
        return  DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$request->arm_id]])
        ->leftJoin('marks', function($join) use($report_id,$subject_id)
        {
            $join->on('marks.student_id', '=', 'students.id')
            ->where([['marks.report_id',$report_id],
                     ['marks.subject_id',$subject_id]]);
        })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
         students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
         marks.exams as exams,marks.subject_id as subject_id'))->orderBy('name')
        ->get();
    }

    }

    public function destroy($id)
    {
        //
    }


    public function getSubjectRank($student_id,$report_id,$subject_id,$arm_id=null){

        $rank=1;
        $rankArr=array();
         $positions=null;
                        if($arm_id){
                          $positions = DB::table('marks')
                    ->where( [
                       ['subject_id',$subject_id],
                       ['report_id',$report_id],
                       ['arm_id',$arm_id]
                       ])->select('student_id','subject_id',"total")->orderBy("total",'desc')
                    ->get();
                        }else{
                   $positions = DB::table('marks')
                    ->where( [
                       ['subject_id',$subject_id],
                       ['report_id',$report_id]
                       ])->select('student_id','subject_id',"total")->orderBy("total",'desc')
                    ->get();
                        }
                 //   return  $this-> Rank($positions,$student_id) ;
          array_push($rankArr,$this->Rank($positions,$student_id));




   return $this->Rank($positions,$student_id);
  }


  public function getSubjectAnnualRank($student_id,$report_id,$subject_id,$arm_id=null){

    $rank=1;
    $rankArr=array();
     $positions=null;
                    if($arm_id){
                      $positions = DB::table('marks')
                ->where( [
                   ['subject_id',$subject_id],
                   ['report_id',$report_id],
                   ['arm_id',$arm_id]
                   ])->select('student_id','subject_id',"annual_total as total")->orderBy("total",'desc')
                ->get();
                    }else{
               $positions = DB::table('marks')
                ->where( [
                   ['subject_id',$subject_id],
                   ['report_id',$report_id]
                   ])->select('student_id','subject_id',"annual_total as total")->orderBy("total",'desc')
                ->get();
                    }
             //   return  $this-> Rank($positions,$student_id) ;
               array_push($rankArr,$this->Rank($positions,$student_id));




return $this->Rank($positions,$student_id);
}

  public function Rank($positions=array() ,$student_id){
                      $rank=1;
                   $total=0;
                   $rank2=0;
                   $counter=1;
                     $num=1;
                  //  return[$positions,$student_id] ;
                  foreach($positions as $position) {


                          if($position->total==$total)
                          {    $rank=$rank-$num;$rank2=$rank ;$num=++$num;}


                          if($position->student_id==$student_id){
                            return ['subject_id'=>$position->subject_id,'position'=>$this->ordinal($rank),'student_id'=>$position->student_id];
                            break;
                                }


        $total=$position->total;
        if($rank2==$rank){$rank=$rank+$num;}else{
            $num=1;
            $rank=++$rank;
        }


    }


    }


  public function Rank2($positions=array() ,$id){
                   $rank=1;
                   $total=0;
                   $rank2=0;
                   $counter=1;
                     $num=1;
                  foreach($positions as $position) {


                          if($position->total==$total)
                          {    $rank=$rank-$num;$rank2=$rank ;$num=++$num;}


                          if($position->student_id==$id){
                            return [$position->student_id,$this->ordinal($rank),$position->total];
                            break;
                                }


        $total=$position->total;
        if($rank2==$rank){$rank=$rank+$num;}else{
            $num=1;
            $rank=++$rank;
        }


    }


    }

    // Class and arm positioning ===sorting students
  public function studentPosition($id,$report_id,$arm=false){
  $report=Report::findOrFail($report_id);
  $student=Student::findOrFail($id);
  $is_history=Level::findOrFail($report->level_id)->is_history;
  $students=null;
  if($arm){
      if($is_history>0){
        $students=Level_history::where([['level_id',$report->level_id],['arm_id',$student->arm_id]])->get();
    }else{
        $students=Student::select('id')->where([['class_id',$report->level_id],['arm_id',$student->arm_id]])->get();
    }


  }
  else{
  $students=Student::select('id')->where('class_id',$report->level_id)->get();
  }
  $studentArr=array();
  $studentPosition=array();
  foreach($students as $student){
  $avgScores=DB::table('marks')->whereNotIn('total',[0])
  -> where([['student_id',$student->id],['type','Academic'],
  ['report_id',$report_id]

  ])
  ->select(DB::raw('avg(total) as Total'),'student_id')

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


public function score_template($report_id,$subject_id){
    $subject=Subject::findOrFail($subject_id);

    $score_template= DB::table('reports')->where('reports.id',$report_id)
       ->join('students','reports.level_id','=','students.class_id')
       ->crossJoin('subjects')->where('subjects.id',$subject_id)
       ->crossJoin('arms','students.arm_id','=','arms.id')
       ->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.id as student_id,
          students.class_id as level_id, arms.name as arms,students.arm_id as arm_id,reports.id as report_id,
          subjects.id as subjects_id,subjects.exams as exams,subjects.ca1 as ca1 ,subjects.ca2 as ca2,
           subjects.ca3 as ca3,subjects.is_history as is_history'))
          ->orderBy('arms')->orderBy('name')
     ->get();
     return response()->json(['score_template'=>$score_template,'subject'=>$subject->name]);
}



public function score_backup($report_id,$subject_id){
    $isPermitted=false;
    $user=auth('api')->user();

    $subject=Subject::findOrFail($subject_id);
       $report=Report::findOrFail($report_id);
       if($user->type=='subjectTeacher'){
           $subjects=Teachersubject::where([['level_id',$report->level_id],['subject_id',$report_id],['staff_id',$user->staff_id]])->first();
             if($subjects){
                $isPermitted=true;
             }


       }
       if($user->type==='admin'|| $user->type==='tutor' ||$user->type==='superadmin'){
        $isPermitted=true;
     }
       if($isPermitted){
    $score_template= DB::table('marks')->where([['marks.report_id',$report_id],['marks.subject_id',$subject_id]])
       ->join('students','marks.student_id','=','students.id')
       ->crossJoin('levels','marks.level_id','=','levels.id')//->where('levels.id','marks.level_id')
       ->crossJoin('arms','marks.arm_id','=','arms.id')
       ->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.id as student_id,
         levels.level_name as level, students.class_id as level_id, arms.name as arms,students.arm_id as arm_id,marks.report_id as report_id,
          marks.subject_id as subjects_id,marks.exams as exams,marks.test1 as ca1 ,marks.test2 as ca2,
           marks.test3 as ca3,marks.is_history as is_history'))
          ->orderBy('arms')->orderBy('name')
     ->get();
     return response()->json(['score_template'=>$score_template,'subject'=>$subject->name]);
}
else{
return 'Not permitted';
}

}



public function studenResult( $report_id, $student_id=null)
{
 // return Mark:: where([['subject_id',98],['level_id',28]])->whereNotIn('report_id',[140,141,22])->get();
    $principal_sign=User::where([['type','principal'],['school_id',auth('api')->user()->school_id]])->select('photo')->first();
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
          // return $pastTotalarray;
// if($report->term_id===3){
//   // return    Mark::where('level_id',28)->whereNotIn('report_id',[22,141,140])->get();
//              $subjects=Mark::where([['student_id',$student_id],['level_id',$report->level_id]])->distinct('subject_id')->pluck('subject_id');

//             for($i=0;$i<count($subjects);++$i){
//               $marks=Mark::where([['report_id',$report_id],['student_id',$student_id],['subject_id',$subjects[$i]]])->first();
//               if($marks && $marks->average>0){
//                   $gradding=$this->grade($marks->average,$report->gradinggroup_id,auth('api')->user()->school_id);
//             $marks->update([
//               'cummulative_grade'=>$gradding['grade'],
//               'cummulative_narration'=>$gradding['narration']
//             ]);
//               }

//           }
//         }

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
        $staff_comment=$this->staffComment($summary->average_scores,$summary->student->class_id,$summary->student->arm_id);
        $LDomain=$this->learningDomain($student_id,$report_id);

        return response()->json(['scores'=>$scores,'summary'=>$summary,'user'=>$user,'pastTotal'=>$pastTotalarray,
    'comment'=>$comment,'principa

    l_comment'=>$principal_comment,'signature'=>$principal_sign, 'staff_comment'=>$staff_comment,
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




    public function staffComment($average,$level_id,$arm_id){

        $comment=Staff_comment::where([['lower_bound','<=',$average],['upper_bound','>=',$average],['school_id',auth('api')->user()->school_id]])
        ->where([['level_id',$level_id],['arm_id',$arm_id]])->first();
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

public function importExcel( Request $request){
//return auth('api')->user()->school_id;
   Excel::import(new MarksImport, $request->file('file'));

   // updating the marks table with subject positioning
  $mark=Mark::latest()->first();
   Markcheck::create([
    'report_id'=>$mark->report_id,
    'subject_id'=>$mark->subject_id,
    'school_id'=>auth('api')->user()->school_id,
]);

CheckResult::create([
 'report_id'=>$mark->report_id,
 'is_history'=>$mark->is_history,
   'school_id'=>auth('api')->user()->school_id,
   'compute_summary'=>$mark->compute_summary
]);
   return 'done';
}



public function computeSummary($report_id){
     // $school_id=auth('api')->user()->school_id;
      $report=Report::findOrFail($report_id);
   // $aStudents=Student::where('class_id',$report->level_id)->pluck('id');
    $is_history=Level::findOrFail($report->level_id)->is_history;
    $students=Mark::whereNotIn('average',[0])->where('report_id',$report_id)->get();
if(count($students)>0){
    CheckResult::create([
        'report_id'=>$report_id,
        'is_history'=> $is_history,
          'school_id'=>auth('api')->user()->school_id,
          'compute_summary'=>1
       ]);
       return ['message'=>'success'];
    }else


    // foreach($students as $student){
    //  $this->resultSummary($report_id,$student->student_id,$student->arm_id,$school_id);
    //   }
   return ['message'=>'no record'];

    }

}
