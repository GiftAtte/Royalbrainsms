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
use App\CrecheDomain;
use App\Level;
use App\Assessment;
use App\CheckResult;
use App\Staff_comment;
use App\TeachersComment;
use App\Result_activation;
use App\Events\MarksCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Level_history;
use App\Markcheck;
use App\Imports\MarksImport;
use App\Teachersubject;
use App\CrechestudentDomain;
//use App\CrechestudentDomain;
use Maatwebsite\Excel\Facades\Excel;
ini_set('max_execution_time', '1000');
class ScoreController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {  $user=auth('api')->user();
        // if($user->type==='tutor'){
        //       $historyLevel=Level::where('is_history',1)->pluck('id');
        //       // $arm=Has_arm::where(['staff_id',$user->staff_id])->whereNotIn('level_id',$historyLevel)->first();
        //     $arm=Has_arm::where('staff_id',$user->staff_id)->whereNotIn('level_id',$historyLevel)->first();
        //     return Report::where([['school_id',$user->school_id],['level_id',$arm->level_id]])->latest()->get();
        // }



        return Report::where('school_id',auth('api')->user()->school_id)->latest()->get();
    }

     public function convertedScore($total,$type){
           if($type==='mid_term'){
               return round((($total/25)*100),2);
           }
           else{
               return $total;
           }
     }
    //weightrd Scores
     public function weightScore($t1,$t2,$t3,$note){


        return round((($t1+$t2+$t3+$note)*0.5),2);
     }
     //Sum

    public function sum($weight_score,$exams,$midterm_score=0){

           $sum=0;
        if(is_numeric($weight_score)){
          $sum=  $sum+ $weight_score;
        }
         if(is_numeric($exams)){
            $sum=  $sum+$exams;
        }
        if(is_numeric($midterm_score)){
            $sum=  $sum+$midterm_score;
        }

        return round($sum,2);
    }

public function AnnualScore($score,$term_id){

    if($term_id<3){
        return round((($score/100)*30),2);
    }
    else{
        return round((($score/100)*40),2);
    }


}


    public function store(Request $request)
    {

        $report=Report::findOrFail($request->report_id);
        if($report->type==='default-result'||$report->type==='default-midterm'){
           return $this->default_store($request);
        }
       $Nstudents= $request->number_of_students;
       //$students=$request->all();
         $weighted_score=null;




            $subject_type=Level_sub::where([['level_id',$report->level_id],['subject_id',$request->subject_id]])->first();
            $term_id=$report->term_id;

     Mark::whereIn('report_id',[$request->report_id])->whereIn('arm_id',[$request->arm_id])->whereIn('subject_id',[$request->subject_id])->delete();
            //return  $student_id;
            for($i=0;$i<$Nstudents; ++$i){
              //  if($request->exams[$i]>0||!empty($request->midterm[$i])){

         // $student_arm=Student::findOrFail($request->student_id[$i]);

          $weighted_score=$this->weightScore($request->test1[$i],$request->test2[$i],$request->test3[$i],$request->note[$i]);
         // $midterm_score=Mark::where([['student_id',$request->student_id[$i]],['subject_id',$request->subject_id],['term_id',$report->term_id],['session_id',$report->session_id],['report_type','mid_term']])->first();
         // $midterm_score=$request->midterm[$i];
          // computing sum and grading
          //return$request->exams[$i];
                $total=$this->sum($weighted_score,$request->exams[$i],$request->midterm?$request->midterm[$i]:0);
               $annual_score= $this->AnnualScore($total,$term_id);
             // $convertedScore=$this->convertedScore($total,$report->type);
                $gradding= $this->grade($total,$report->gradinggroup_id,auth('api')->user()->school_id);
                //$annual_gradding= $this->grade($annual_score,$report->gradinggroup_id,auth('api')->user()->school_id);
            // return $gradding;

  // return $this->studentPosition($request->student_id[$i+2],$request->report_id,false);

         Mark::create(
               [ 'student_id'=>$request->student_id[$i],
                'report_id'=>$request->report_id,
                'level_id'=>$report->level_id,
                'subject_id'=>$request->subject_id,
                      'test1'=>$request->test1[$i],
                      'test2'=>$request->test2[$i],
                      'test3'=>$request->test3[$i],
                      'note'=>$request->note[$i],
                      'weighted_score'=>$weighted_score,
                      'exams'=>$request->exams[$i],
                      'total'=>$total,
                      'grade'=>$gradding['grade'],
                      'narration'=>$gradding['narration'],
                      'term_id'=>$term_id,
                      'arm_id'=>$request->arm_id[$i],
                      'type'=>$subject_type->type,
                      'annual_score'=>$this->AnnualScore($total,$term_id),
                      'report_type'=>$report->type,
                      'mid_term'=>$request->midterm?$request->midterm[$i]:0,
                      'session_id'=>$report->session_id,
                      'is_history'=>0
            ]
           );
         //   Mark::insert($scores);



          //}
        }
    Markcheck::create([
     'report_id'=>$request->report_id,
     'subject_id'=>$request->subject_id,
     'school_id'=>auth('api')->user()->school_id,
 ]);

//  CheckResult::create([
//   'report_id'=>$request->report_id,
//   'is_history'=>0,
//     'school_id'=>auth('api')->user()->school_id,
//     'compute_summary'=>1
//  ]);

    return ['message'=>'success'];
    }

// import scores

public function import(Request $request)
{



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

        // $avg=DB::table('marks')->whereNotIn('total',[0])
        // -> where([['student_id',$student->id],['type','Academic'],
        // ['report_id',$report_id]

        // ])->avg('total');



        $report=Report::findOrFail($report_id);

      $total_student=Student::where([['class_id',$report->level_id],['arm_id',$arm_id]])->count();
    //    $students=Mark::where('report_id',$report_id)->whereNotIn('total',[0])
    //                   ->select('student_id')->distinct('student_id')->get();

      //$student_by_arm=Student::where
      $level_id=$report->level_id;
    //  $total_student=count($students);
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
                                'arm_position'=>'',
                                'arm_id'=>$arm_id,
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
return ["grade"=>'-',"narration"=>'-','credit_point'=>0,'total'=>0];
}




    public function loadStudents(Request $request)
    {
             $historyLevel=Level::where('is_history',1)->pluck('id');
         $arm=Has_arm::whereNotIn('level_id',$historyLevel)->where('staff_id',auth('api')->user()->staff_id)->first();

        $report_id=$request->report_id;
        $subject_id=$request->subject_id;
        $report=Report::findOrFail($request->report_id);
    //       if(auth('api')->user()->type==='tutor'){
    //   return  DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$arm->arms_id]])
    //     ->leftJoin('marks', function($join) use($report_id,$subject_id)
    //     {
    //         $join->on('marks.student_id', '=', 'students.id')
    //         ->where([['marks.report_id',$report_id],
    //                  ['marks.subject_id',$subject_id]]);
    //     })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
    //      students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
    //      marks.exams as exams,marks.subject_id as subject_id'))->orderBy('name')
    //     ->get();

    // }else{
                    if($report->type==='terminal'){

   $terminal=DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$request->arm_id]])
        ->leftJoin('marks', function($join) use($report_id,$subject_id)
        {
            $join->on('marks.student_id', '=', 'students.id')
            ->where([['marks.report_id',$report_id],
                     ['marks.subject_id',$subject_id]]);
        })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
         students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
         marks.exams as exams,marks.subject_id as subject_id, marks.note as note'))->orderBy('name');
      //  ->get();
     // return collect($terminal->get());

       $list= DB::table('marks')->where([
        ['marks.subject_id',$subject_id],['marks.term_id',$report->term_id], ['marks.level_id',$report->level_id],
        ['marks.report_type','mid_term']  ])->orWhere([
            ['marks.subject_id',$subject_id],['marks.term_id',$report->term_id], ['marks.level_id',$report->level_id],
            ['marks.report_type','default_midterm']  ])
            ->joinSub($terminal, 'terminals', function ($join) use($report,$subject_id) {
                $join->on('marks.student_id', '=', 'terminals.student_id');
            })->select('terminals.*','marks.total as total')->distinct('student_id')
            ->orderBy('name')->get();

                if(count($list)>0){
                    return collect($list)->unique('student_id')->values()->all();
                }else{
                    return collect($terminal->get());

                }
}


// default mid term
if($report->type==='default-result'){

    $terminal=DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$request->arm_id]])
         ->leftJoin('marks', function($join) use($report_id,$subject_id)
         {
             $join->on('marks.student_id', '=', 'students.id')
             ->where([['marks.report_id',$report_id],
                      ['marks.subject_id',$subject_id]]);
         })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
          students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
          marks.exams as exams,marks.subject_id as subject_id, marks.note as note'))->orderBy('name');
       //  ->get();
      // return collect($terminal->get());

        $list= DB::table('marks')->where([
             ['marks.subject_id',$subject_id],['marks.term_id',$report->term_id], ['marks.level_id',$report->level_id],
             ['marks.report_type','default-midterm']  ])
             ->joinSub($terminal, 'terminals', function ($join) use($report,$subject_id) {
                 $join->on('marks.student_id', '=', 'terminals.student_id');
             })->select('terminals.*','marks.total as total')->distinct('student_id')
             ->orderBy('name')->get();

                 if(count($list)>0){
                     return collect($list)->unique('student_id')->values()->all();
                 }else{
                     return collect($terminal->get());

                 }
 }



        return  DB::table('students')->where([['class_id',$report->level_id],['students.arm_id',$request->arm_id]])
        ->leftJoin('marks', function($join) use($report_id,$subject_id)
        {
            $join->on('marks.student_id', '=', 'students.id')
            ->where([['marks.report_id',$report_id],
                     ['marks.subject_id',$subject_id]]);
        })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
         students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
         marks.exams as exams,marks.subject_id as subject_id, marks.note as note'))->orderBy('name')
        ->get();


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
  public function studentPosition($id,$report_id,$arm=true){
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

   ->orderBy('Total','DESC')
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
    //Mark::whereIn('report_id',[314,315,317,318,377,378,379,380,0])->delete();
    $scoreArr=[];
    $scores=null;
    $summary=null;
 // return Mark:: where([['subject_id',98],['level_id',28]])->whereNotIn('report_id',[140,141,22])->get();
    $principal_sign=User::where([['type','principal'],['school_id',auth('api')->user()->school_id]])->select('photo')->first();
      if($student_id===null){
        $student_id=auth('api')->user()->student_id;
      }


        $comment=Result_activation::where([['report_id',$report_id],['student_id',$student_id]])->first();
      $report=Report::with(['levels','sessions','terms'])->where('id',$report_id)->first();
           // MADONNA ANNUAL
    // $pastTotal=Mark::select('annual_score','subject_id','term_id','total')
    //      ->where([['student_id',$student_id],['level_id',$report->level_id]
    //      ])->whereNotIn('report_type',['mid_term'])->distinct('term_id')->get();

// Thinks School Annual
         $pastTotal=Mark::select('total as annual_score','subject_id','term_id','report_id')
         ->where([['student_id',$student_id],['level_id',$report->level_id]
         ])->whereNotIn('report_type',['mid_term','default-midterm'])->whereNotIn('term_id',[4,3])->distinct(['term_id','subject_id'])->get();



          $pastTotalarray=[];
          foreach ($pastTotal as $total ) {

              array_push($pastTotalarray,['subject_id'=>$total->subject_id,'term_id'=>$total->term_id,'total'=>$total->annual_score]);
              # code...
          }
          $collect= collect($pastTotalarray)->all();
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
      //Mark::where('report_id',$report_id)->whereNotIn('subject_id',$level_sub)->distinct('subject_id')->delete();
$student=Student::findOrFail($student_id);
     $arm=Arm::findOrFail($student->arm_id);
    // $this->resultSummary($report_id, $student_id,$student->arm_id);
    $user=User::with(['students','school'])->where('student_id',$student_id)->first();
    $grading=Grading::whereIn('group_id',[$report->gradinggroup_id])->where('school_id',$user->school_id)->get();

if($report->type==='creche'){

    $domains=CrechestudentDomain::where([['report_id',$report_id],['student_id',$student_id]])->distinct('domain_id')->pluck('domain_id');
    for($i=0;$i<count($domains);++$i){

        $subdomains=  CrechestudentDomain::where([['domain_id',$domains[$i]],['report_id',$report_id],['student_id',$student_id]])->with(['ratings','subdomains'])->get();
        $domain=CrecheDomain::findOrFail($domains[$i]);
            array_push($scoreArr,['domain'=>$domain->name,'subdomains'=>$subdomains]);
    }
    $scores=collect($scoreArr);
    $summary=CrechestudentDomain::with(['student'])->where([['report_id',$report_id],['student_id',$student_id]])->first();
}else{
   $scores=Mark::whereNotIn('total',[0])->with('subjects')->where([['report_id',$report_id],
    ['student_id',$student_id],['type','Academic']])->distinct('subject_id')->get();
    $summary=Result::with(['student'])->where([['report_id',$report_id],['student_id',$student_id]])->first();
}
    $noneAcademic=Mark::whereNotIn('total',[0])->with('subjects')->where([['report_id',$report_id],
    ['student_id',$student_id],['type','None Academic']])->get();


      $principal_comment=$this->principalComment($summary?$summary->average_scores:0,$report->gradinggroup_id);
       $staff_comment=$this->staffComment($student_id,$report_id);
        $LDomain=$this->learningDomain($student_id,$report_id);

        return response()->json(['principal_comment'=>$principal_comment,'scores'=>$scores,'summary'=>$summary,'user'=>$user,'pastTotal'=>$collect,
    'comment'=>$comment,'signature'=>$principal_sign, 'staff_comment'=>$staff_comment,
     'report'=>$report,'arm'=>$arm,'gradings'=>$grading,'noneAcademic'=>$noneAcademic,'LDomain'=>$LDomain]);



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

public function importExcel( Request $request){



if($request->has('file')){
$data=array_map('str_getcsv',file($request->file));
  $header=$data[0];
    unset($data[0]);
    $score_array2=[];
    $score_array=[];
    $report_id=intval(array_combine($header,$data[1])['report_id']);
    $subject_id=intval(array_combine($header,$data[1])['subjects_id']);
    $report=Report::findOrFail($report_id);
   $subject=Level_sub::findOrFail($subject_id);
    // retrieve the scores for the level
   Mark::whereIn('subject_id',[$subject_id])->whereIn('report_id',[$report_id])->delete();
    $LevelScores=Mark::whereIn('subject_id',[$subject_id])->whereIn('level_id',[$report->level_id])->get();

    foreach($data as $score){

        $score=array_combine($header,$score);
        $score['total']=$this->default_sum($score['ca1'],$score['ca2'],$score['ca3'],$score['exams']);
if($score['total']>0){
        array_push($score_array, $score);
}
    }

  $totals=collect($score_array);

  $arm_ids=$totals->unique('arm_id')->toArray();
   // $level_ids=$totals->pluck('level_id')->unique();
  // return $arm_ids->toArray();
    foreach($arm_ids  as $arm_id){

$armScores=$totals->whereIn('arm_id',$arm_id['arm_id'])->sortByDesc('total')->values();
$CurrentlevelScores=$totals->sortByDesc('total')->values();
//$lev=$LevelScores->whereIn('arm_id',[$arm_ids[$i]]);

         foreach($armScores  as $score){
             $CAvg=0;
             $CGrade='';
             $CNarration='';
             $grand_total=0;
             $averageScore=collect($LevelScores)->where('student_id',$score['student_id'])->avg('total');
               $grand_total=collect($LevelScores)->where('student_id',$score['student_id'])->sum('total');
             $grade=$this->grade(floatval($score['total']),$report->gradinggroup_id,auth('api')->user()->school_id);

             $score['arm_subj_position']=$this->getRanking($armScores,$score['total']);
             $score['class_subj_position']=$this->getRanking($CurrentlevelScores,$score['total']);

             $score['grade']= $grade['grade'];
         $score['narration']= $grade['narration'];
         $score['average']=round($totals->avg('total'),2);
         $score['term_id']=$report->term_id;
         $score['report_id']=$report_id;
         $score['subject_id']=$subject_id;
          $score['arm_id']=intval($score['arm_id']);
         $score['level_id']=$report->level_id;
         $score['report_type']=$report->type;
         $score['type']=$subject->type;
          $score['exams']=floatval($score['exams']);
         $score['test1']=floatval($score['ca1']);
         $score['test2']=floatval($score['ca2']);
         $score['test3']=floatval($score['ca3']);
         $score['is_history']=intval($score['arm_id']);
         $score['arm_max_score']=$armScores->max('total');
         $score['arm_min_score']=$armScores->min('total');
         $score['arm_avg_score']=round($armScores->avg('total'),2);
          if($averageScore>0){
               $CAvg=round((($score['total']+$averageScore)/2),2);

               $CGrade=$this->grade(floatval($CAvg),$report->gradinggroup_id,auth('api')->user()->school_id);
          }else{
                $CAvg=round($score['total'],2);
                $CGrade=$grade;
          }
          $score['cummulative_avg']=$CAvg;
          $score['grand_total']=round($grand_total+$score['total'],2);
          $score['cummulative_grade']=$CGrade['grade'];
          $score['cummulative_narration']=$CGrade['narration'];

        Mark::create($score);
         array_push($score_array2, collect($score));



    }


                //    'arm_subj_position'=>$arm_sub_position,
                //  //'max_class_score'=>$max_score,
                //   //'min_class_score'=>$min_score,
                //             'average'=>round($cummulative_avg,2),
                //             'grand_total'=>round($grand_total,2),
                //             'arm_avg_score'=>round($arm_avg_score,2),
                //             'arm_min_score'=>round($arm_min_score,2),
                //             'arm_max_score'=>round($arm_max_score,2),
                //             'cummulative_grade'=>$cGradding['grade'],
                //             'cummulative_narration'=>$cGradding['narration'],




        // return $score;



    //          Mark::where([['report_id',$request->report_id], ['student_id', $request->student_id[$i]],['subject_id',$request->subject_id]])->delete();
    //          Mark::create(


    //            [ 'student_id'=>$request->student_id[$i],
    //             'report_id'=>$request->report_id,
    //             'level_id'=>$report->level_id,
    //             'subject_id'=>$request->subject_id,
    //                   'test1'=>$request->test1[$i],
    //                   'test2'=>$request->test2[$i],
    //                   'test3'=>$request->test3[$i],
    //                   'exams'=>$request->exams[$i],
    //                   'total'=>$total,
    //                   'grade'=>$gradding['grade'],
    //                   'narration'=>$gradding['narration'],
    //                   'term_id'=>$term_id,
    //                   'arm_id'=>$student_arm->arm_id,
    //                   'type'=>$subject_type->type,
    //                   'mid_term'=>$request->midterm?$request->midterm[$i]:0,
    //                   'report_type'=>$report->type,
    //                   'is_history'=>0
    //         ]
    //        );
       }

//$LevelScores->where('arm_id',$arm_ids[$i])->delete();
  // return'success';
   return collect($score_array2)->sortByDesc('total') ;
}


  // Excel::import(new MarksImport, $request->file('file'));

   // updating the marks table with subject positioning
//   $mark=Mark::latest()->first();
//    Markcheck::create([
//     'report_id'=>$mark->report_id,
//     'subject_id'=>$mark->subject_id,
//     'school_id'=>auth('api')->user()->school_id,
// ]);

//MarksImport::MarksCompute();



// CheckResult::create([
//  'report_id'=>$mark->report_id,
//  'is_history'=>$mark->is_history,
//    'school_id'=>auth('api')->user()->school_id,
//    'compute_summary'=>$mark->compute_summary
// ]);
   return 'No File';
}



public function computeSummary($report_id,$arm_id){







        $aStudents=null;
        $report=Report::findOrFail($report_id);
       $is_history=Level::findOrFail($report->level_id)->is_history;
       if($is_history>0){
        $aStudents=Level_history::where([['level_id',$report->level_id],['arm_id',$arm_id]])->pluck('student_id');

       }else{
        $aStudents=Student::where([['class_id',$report->level_id],['arm_id',$arm_id]])->pluck('id');
       }

               $students=Mark::whereNotIn('total',[0])->where('report_id',$report_id)
               ->whereIn('student_id',$aStudents)
               ->select('student_id','arm_id')->distinct('student_id')->get();


if(count($students)>0){
               foreach($students as $student){
                    $this->resultSummary($report_id,$student->student_id,$arm_id,$report->school_id);
                  }

                  $collect=collect(DB::table('results')->where([['average_scores','>',0],['report_id',$report_id],['arm_id',$arm_id]])
                  ->select('total_scores as total','student_id')->orderBy('total','DESC')->get());

                 // ['report_id',$report_id]

                 // ])->avg('total');
                         foreach($students as $student){
                           //$collect=collect(collect($avgArr))->orderBy('total','DESC')->all();
                               //$data=$collect->orderBy('total','DESC')->all();
                           //if($armScores=$collect->where('arm_id',$student->arm_id)->get()){;
                           $score=DB::table('results')->where([['student_id',$student->student_id],['report_id',$report_id]])->first();

                          if($score){
                           Result::where([['student_id',$student->student_id],['report_id',$report_id]])
                           ->update([
                               'arm_position'=>$this->getRanking($collect,$score->total_scores)
                           ]);
                         }}



               // $this->resultSummary($checkreport->report_id,$checkreport->is_history);

  return ['message'=>'success'];
    //     }else
                }else{
                    return['message'=>'no record'];
                }
}




//      // $school_id=auth('api')->user()->school_id;
//       $report=Report::findOrFail($report_id);
//    // $aStudents=Student::where('class_id',$report->level_id)->pluck('id');
//     $is_history=Level::findOrFail($report->level_id)->is_history;
//     $students=Mark::whereNotIn('total',[0])->where('report_id',$report_id)->get();
// if(count($students)>0){
//     CheckResult::create([
//         'report_id'=>$report_id,
//         'is_history'=> $is_history,
//           'school_id'=>auth('api')->user()->school_id,
//           'compute_summary'=>1
//        ]);
//        return ['message'=>'success'];
//     }else


//     // foreach($students as $student){
//     //  $this->resultSummary($report_id,$student->student_id,$student->arm_id,$school_id);
//     //   }
//    return ['message'=>'no record'];

//}



    public function default_store(Request $request)
    {
    $Nstudents= $request->number_of_students;
    $students=$request->student_id;



            $report=Report::findOrFail($request->report_id);
            $subject_type=Level_sub::where([['level_id',$report->level_id],['subject_id',$request->subject_id]])->first();
            $term_id=$report->term_id;

             Mark::whereIn('report_id',[$request->report_id])->whereIn('arm_id',[$request->arm_id])->whereIn('subject_id',[$request->subject_id])->delete();
            for($i=0;$i<$Nstudents; ++$i){
          $student_arm=Student::findOrFail($request->student_id[$i]);

            // computing sum and grading
             $total=$this->default_sum($request->test1[$i],$request->test2[$i],$request->test3[$i],$request->exams[$i],$request->midterm?$request->midterm[$i]:0);
            $gradding= $this->grade($total,$report->gradinggroup_id,auth('api')->user()->school_id);

            // return $gradding;

  // return $this->studentPosition($request->student_id[$i+2],$request->report_id,false);
 //Mark::where([['report_id',$request->report_id], ['student_id', $request->student_id[$i]],['subject_id',$request->subject_id]])->delete();
         Mark::create(


               [ 'student_id'=>$request->student_id[$i],
                'report_id'=>$request->report_id,
                'level_id'=>$report->level_id,
                'subject_id'=>$request->subject_id,
                      'test1'=>$request->test1[$i],
                      'test2'=>$request->test2[$i],
                      'test3'=>$request->test3[$i],
                      'exams'=>$request->exams[$i],
                      'total'=>$total,
                      'grade'=>$gradding['grade'],
                      'narration'=>$gradding['narration'],
                      'term_id'=>$term_id,
                      'arm_id'=>$student_arm->arm_id,
                      'type'=>$subject_type->type,
                      'mid_term'=>$request->midterm?$request->midterm[$i]:0,
                      'report_type'=>$report->type,
                      'is_history'=>0
            ]
           );
         //   Mark::insert($scores);


          }

    //   $students_score=DB::table('marks')->whereNotIn('total',[0])->where([['report_id',$request->report_id],['subject_id',$request->subject_id]])->select('total')->groupBy('total')->get()->toArray();
    //   return    $subject_positions=$this->getSubjectRank(94,$students_score);
    //   // updating the marks table with subject positioning
  //  $mark=Mark::latest()->first();
    Markcheck::create([
     'report_id'=>$request->report_id,
     'subject_id'=>$request->subject_id,
     'gradinggroup_id'=>$report->gradinggroup_id,
     'arm_id'=>$request->arm_id[0],
     'school_id'=>auth('api')->user()->school_id,
 ]);

//  CheckResult::create([
//   'report_id'=>$request->report_id,
//   'is_history'=>0,
//     'school_id'=>auth('api')->user()->school_id,
//     'compute_summary'=>1
//  ]);
 return ['message'=>'default success'];
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


public function getRanking($scoresCollection,$total){
        //return$scoresCollection;
       //$orderResult=$scoresCollection->groupBy('total');
     $data = $scoresCollection->sortByDesc('total')->where('total', $total);
         $value = $data->keys()->first() + 1;
    return $this->ordinal($value);
 }

}
