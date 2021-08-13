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
              // return $request->all();
               $request->subject_id;
               $Nstudents= $request->number_of_students;

        $marksArray=[];
           $report=Report::findOrFail($request->report_id);
        Mark::whereIn('report_id',[$request->report_id])->where([['arm_id',$request->arm_id],['subject_id',$request->subject_id]])->delete();
           //Mark::where()
            $subject=Level_sub::where([['level_id',$report->level_id],['subject_id',$request->subject_id]])->first();
           $LevelScores=Mark::whereIn('level_id',[$report->level_id])->where([['subject_id',$request->subject_id],['arm_id',$request->arm_id]],)
            ->whereNotIn('report_type',['mid_term']) ->get();


            for($i=0;$i<$Nstudents; ++$i){
                 $weighted_score=$this->weightScore($request->test1[$i],$request->test2[$i],$request->test3[$i],$request->note[$i]);
                $total=$this->sum($weighted_score,$request->exams[$i],$request->midterm?$request->midterm[$i]:0);
                $annual_score= $this->AnnualScore($total,$report->term_id);
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
          $mark['note']=$request->note[$i];
          $mark['arm_id']=$request->arm_id;
          $mark['weighted_score']=$weighted_score;
          $mark['total']=$total;
          $mark['grade']=$gradding['grade'];
          $mark['narration']=$gradding['narration'];
          $mark['term_id']=$report->term_id;
          $mark['type']=$subject->type;
          $mark['annual_score']=$annual_score;
          $mark['mid_term']=$request->midterm?$request->midterm[$i]:0;
          $mark['report_type']=$report->type;
          $mark['is_history']=0;
          $annualScore=collect($LevelScores)->where('student_id',$request->student_id[$i])->pluck('annual_score');
          $annual_total=collect($annualScore)->push($annual_score)->sum();
          $mark['annual_total']=round($annual_total,2);

           array_push($marksArray,$mark);


          }

       $armScores=collect($marksArray)->whereNotIn('total',[0])
       ->sortByDesc('total')->values();
      $CurrentlevelScores=collect($LevelScores);

         $count=0;
         $score_array2=[];
        foreach($marksArray  as $score){

             $CAvg=0;
             $CGrade=['grade'=>'','narration'=>''];
             $CNarration='';
             $grand_total=0;


             //$grand_total=collect($LevelScores)->where('student_id',$score['student_id'])->sum('annual_score');
             $score['arm_subj_position']=$this->getRanking($armScores,$score['total']);
             //$score['class_subj_position']=$this->getRanking($CurrentlevelScores,$score['total']);


         $score['arm_max_score']=collect($marksArray)->max('total');
         $score['arm_min_score']=collect($marksArray)->whereNotIn('total',[0])->min('total');
         $score['arm_avg_score']=round(collect($marksArray)->whereNotIn('total',[0])->avg('total'),2);
         $CGrade=$this->grade(floatval($annual_total),$report->gradinggroup_id,auth('api')->user()->school_id);

          $score['annual_position']=$this->annualRanking($armScores,$score['annual_total']);
          $score['annual_average']=round(collect($marksArray)->avg('annual_total'),2);

          $score['annual_grade']=$CGrade['grade'];
          $score['annual_narration']=$CGrade['narration'];
                      //$annual_total=DB::table('marks')->whereNotIn('annual_score',[0])->where([['subject_id',$markcheck->subject_id],['session_id',$report->session_id],['student_id',$student->student_id]])->sum('annual_score');
                      //$annual_average=DB::table('mark')->whereNotIn('annual_score',[0])->where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['arm_id',$student->arm_id]])->sum('annual_score');

                       // $annual_grade=$this->grade($annual_total,$report->gradinggroup_id,$markcheck->school_id);


                           //  'annual_total'=>round($annual_total,2),
                           // 'annual_grade'=>$annual_grade['grade'],
                           // 'annual_narration'=>$annual_grade['narration'],
                           // 'annual_position'=>$annual_position['position'],

    //    ]
    //  );

    //   $mark=Mark::where([['subject_id',$markcheck->subject_id],['report_id',$markcheck->report_id],['student_id',$student->student_id]])->first();
    //   $annual_total=DB::table('marks')->whereNotIn('annual_score',[0])->where([['subject_id',$markcheck->subject_id],['session_id',$report->session_id],['student_id',$student->student_id]])->avg('annual_total');
    //  // $annual_position=DB::table('marks')->whereNotIn('annual_score',[0])->where([['subject_id',$markcheck->subject_id],['session_id',$report->session_id],['student_id',$student->student_id]])->avg('annual_total');
    //   $annual_position=$scoreController->getSubjectAnnualRank($student->student_id,$student->report_id,$markcheck->subject_id,$student->arm_id);

    //    $mark->annual_position=$annual_position['position'];
    //    $mark->annual_total=$annual_total;

    //  $mark->save();



    //return$score;
            Mark::create($score);


    }

 return 'success';




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






// Gradding



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

   $terminal=DB::table('students')->where([['students.class_id',$report->level_id],['students.arm_id',$request->arm_id]])
        ->leftJoin('marks', function($join) use($report_id,$subject_id)
        {
            $join->on('marks.student_id', '=', 'students.id')
            ->where('marks.report_id',$report_id)
            ->where('marks.subject_id',$subject_id);

        })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
         students.id as student_id,marks.test1 as test1,marks.test2 as test2, marks.test3 as test3,
         marks.exams as exams,marks.subject_id as subject_id, marks.note as note'))->orderBy('name');
      //  ->get();
     //return collect($terminal->get());

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
   $scores=Mark::whereIn('report_id',[$report_id])->with('subjects')
    ->where([['student_id',$student_id],['type','Academic']])->whereNotIn('total',[0])
    ->distinct('subject_id')->get();
    $summary=Result::with(['student'])->where([['report_id',$report_id],['student_id',$student_id]])->first();
}
    $noneAcademic=Mark::whereIn('report_id',[$report_id])->with('subjects')
    ->where([['student_id',$student_id],['type','None Academic']])->whereNotIn('total',[0])
    ->distinct('subject_id')->get();


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
     $isDelete=  Mark::whereIn('report_id',[$report_id])->whereIn('subject_id',[$subject_id])->delete();
    $subject=Level_sub::where('subject_id',$subject_id)->first();
    // retrieve the scores for the level

    $LevelScores=Mark::whereIn('level_id',[$report->level_id])->where('subject_id',$subject_id)->get();

    foreach($data as $score){

        $score=array_combine($header,$score);
        $score['total']=$this->default_sum($score['ca1'],$score['ca2'],$score['ca3'],$score['exams']);
if($score['total']>0){
        array_push($score_array, $score);
}
    }

  $totals=collect($score_array);

  $arm_ids=$totals->unique('arm_id')->values();
   // $level_ids=$totals->pluck('level_id')->unique();
  // return $arm_ids->toArray();
    foreach($arm_ids  as $arm_id){

$armScores=$totals->whereIn('arm_id',$arm_id['arm_id'])->sortByDesc('total')->values();
$armScoresTotal=$armScores;

$CurrentlevelScores=$totals->sortByDesc('total')->values();
//$lev=$LevelScores->whereIn('arm_id',[$arm_ids[$i]]);

         foreach($armScores  as $score){
             $CAvg=0;
             $CGrade='';
             $CNarration='';
             $grand_total=0;
           $averageScore=collect($LevelScores)->where('student_id',$score['student_id'])->pluck('total');
           $averageScore=  collect($averageScore)->push($score['total'])->avg();
               $grand_total=collect($LevelScores)->where('student_id',$score['student_id'])->sum('total');
             $grade=$this->grade(floatval($score['total']),$report->gradinggroup_id,auth('api')->user()->school_id);

             $score['arm_subj_position']=$this->getRanking($armScoresTotal,$score['total']);
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
         $score['test1']=$score['ca1'];
         $score['test2']=$score['ca2'];
         $score['test3']=$score['ca3'];
         $score['is_history']=$score['is_history'];
         $score['arm_max_score']=collect($armScores)->max('total');
         $score['arm_min_score']=collect($armScores)->min('total');
         $score['arm_avg_score']=round(collect($armScores)->avg('total'),2);


          $CGrade=$this->grade(floatval($averageScore),$report->gradinggroup_id,auth('api')->user()->school_id);

          $score['cummulative_avg']=round($averageScore,2);
          $score['grand_total']=round(($grand_total+$score['total']),2);
          $score['cummulative_grade']=$CGrade['grade'];
          $score['cummulative_narration']=$CGrade['narration'];
//return $score;
        Mark::create($score);




    }


       }

   return 'success' ;
}

   return 'No File';
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

$averageScore=collect($scores)->where('student_id',$students[$i])->whereNotIn('total',[0])->avg('total');
$total=collect($scores)->where('student_id',$students[$i])->sum('total');
$annual_total=collect($scores)->where('student_id',$students[$i])->sum('annual_total');
$annual_average=collect($scores)->where('student_id',$students[$i])->average('annual_total');

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
    // if($report->term_id===3){

    // }
    $score['grade']=$grade['grade'];
     $score['narration']=$grade['narration'];
   // return $report->type;
     if($report->type==='terminal'){
        $score['progress_status']=$this->grade($score['annual_average'],$report->gradinggroup_id,auth('api')->user()->school_id)['progress_status'];
      $score['annual_grade']=$this->grade($score['annual_average'],$report->gradinggroup_id,auth('api')->user()->school_id)['grade'];
     }else{
     $score['progress_status']=$this->grade($score['cummulative_average'],$report->gradinggroup_id,auth('api')->user()->school_id)['progress_status'];
     }
//return $score;
     Result::create($score);

///return $score;

}

  return ['message'=>'success'];

                }else{
                    return['message'=>'no record'];
                }
}




    public function default_store(Request $request)
    {
  $request->subject_id;
    $Nstudents= $request->number_of_students;

        $marksArray=[];
 $report=Report::findOrFail($request->report_id);
 Mark::whereIn('report_id',[$request->report_id])->where([['arm_id',$request->arm_id],['subject_id',$request->subject_id]])->delete();

            $subject=Level_sub::where([['level_id',$report->level_id],['subject_id',$request->subject_id]])->first();
            $LevelScores=Mark::whereIn('subject_id',[$request->subject_id])->where([['level_id',$report->level_id],['arm_id',$request->arm_id]],)
             ->get();


            for($i=0;$i<$Nstudents; ++$i){
                $total=$this->default_sum($request->test1[$i],$request->test2[$i],$request->test3[$i],$request->exams[$i]);
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
          $mark['total']=$total;
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

            Mark::create($score);


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


public function getRanking($scoresCollection,$total){
     $data = $scoresCollection->sortByDesc('total')->where('total', $total);
         $value = $data->keys()->first() + 1;
    return $this->ordinal($value);
 }
 public function annualRanking($scoresCollection,$total){
     $data = $scoresCollection->sortByDesc('annual_total')->where('annual_total', $total);
         $value = $data->keys()->first() + 1;
    return $this->ordinal($value);
 }

}
