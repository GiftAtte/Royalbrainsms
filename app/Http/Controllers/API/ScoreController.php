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
use App\Http\Controllers\API\Traits\CummulativeTrait;
use App\Http\Controllers\API\Traits\RankingTrait;
use App\Http\Controllers\API\Traits\ResultTraits;
use App\Http\Controllers\API\Traits\ScoreTraits;
use App\Http\Controllers\API\Traits\SummaryTrait;
use App\Http\Controllers\API\Traits\WeeklyAssesmentTrait;
use App\Level;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Teachersubject;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
ini_set('max_execution_time', '1000');
class ScoreController extends Controller
{   use RankingTrait;
    use ResultTraits;
    use ScoreTraits;
    use CummulativeTrait;
    use SummaryTrait;
    use WeeklyAssesmentTrait;
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



        return Report::whereIn('school_id',[auth('api')->user()->school_id])
        ->where('is_ready','<',1)->latest()->get();
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
        //return $request->all();
         $report=Report::findOrFail($request->report_id);
        if($report->type==='default-result'
        ||$report->type==='default-midterm'
        ||$report->type==='diamond'
        ||$report->type==='navy-template'
        ||$report->type==='blue_ridge'
        ){
           return $this->default_store($request);
        }
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
         $CGrade=$this->grade(floatval($score['annual_total']),$report->gradinggroup_id,auth('api')->user()->school_id);

          $score['annual_position']=$this->annualRanking($armScores,$score['annual_total']);
          $score['annual_average']=round(collect($marksArray)->avg('annual_total'),2);

          $score['annual_grade']=$CGrade['grade'];
          $score['annual_narration']=$CGrade['narration'];

            Mark::create($score);


    }
$this->cummulativePerformance($request->report_id,$request->subject_id,$report->type);
$this->computeSummary($request->report_id,$request->arm_id);
 return 'success!!!!!';




    }








    // results summary





// Gradding





    public function loadStudents(Request $request)
    {
             $historyLevel=Level::where('is_history',1)->pluck('id');
         $arm=Has_arm::whereNotIn('level_id',$historyLevel)->where('staff_id',auth('api')->user()->staff_id)->first();

        $report_id=$request->report_id;
        $subject_id=$request->subject_id;
        $report=Report::findOrFail($request->report_id);

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

          if($report->isWeeklyCa>0){
            return $this->loadStudentsWithWeekActivity( $report,$subject_id,$request->arm_id);
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





 public function deleteScores(Request $request){
     try{
     Mark::whereIn('report_id',[$request->report_id])
     ->where([['subject_id',$request->subject_id],['arm_id',$request->arm_id]])
     ->delete();
     }
     catch(Exception $err){
              return ['Message'=>'No results found'];
     }
     return ['Message'=>'Scores removed Successfully'];
 }


    public function loadPerformance(Request $request){
          $report=Report::findOrFail($request->report_id);
          $report_id=$report->id;
          $subject_id=$request->subject_id;
     $ids=Mark::whereIn('report_id',[$report_id])->whereNotIn('total',[0])->where('subject_id',$subject_id)->pluck('student_id');
         return Student::with('arm')->whereIn('students.id',$ids)
        ->leftJoin('marks', function($join) use($report_id,$subject_id)
        {
            $join->on('marks.student_id', '=', 'students.id')
            ->where([['marks.report_id',$report_id],
                     ['marks.subject_id',$subject_id]]);
        })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.arm_id as arm_id,
         students.id as student_id,
         marks.total as total, marks.grand_total as grand_total, marks.cummulative_avg as average, marks.arm_subj_position as position'))->orderBy('average','desc')
        ->get();
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
}
