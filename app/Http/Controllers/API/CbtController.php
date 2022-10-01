<?php

namespace App\Http\Controllers\API;

use App\Answer;
use App\Assignment;
use App\Cbtresult;
use App\CheckResult;
use App\Exam;
use App\Http\Controllers\Controller;
use App\Mark;
use App\Markcheck;
use App\Question;
use App\Question_answer;
use App\Question_option;
use App\Level_sub;
use App\Student;
use App\Studentassignment;
use Illuminate\Http\Request;
use App\Grading;
use App\Report;
use Illuminate\Support\Facades\DB;

class CbtController extends Controller
     {
    public function takeExam($id){
          $cbt=[];
          $score=0;
          $isSubmitted=false;
          $exam=Exam::with(['level','subject'])->where('id',$id)->first();
        $questions =Question::where('exam_id',$id)->get();
        foreach($questions as $question){
            $options=Question_option::with('options')->where('question_id',$question->id)->get();
            array_push($cbt,['question'=>['question'=>$question->question,'id'=>$question->id],'options'=>$options]);

        }
        $check_submitted=Answer::where([['student_id',auth('api')->user()->student_id],['exam_id',$id]])->get();
       if(count($check_submitted)>0){
           $score=$this->cbtResults($id);
        $isSubmitted=true;
       }

           return[ 'cbt'=>$cbt,'exam'=>$exam,'isSubmitted'=>$isSubmitted,'score'=>$score];
    }


    public function saveAnswers(Request $request){
           $questions=$request->questions;
           $student_id=auth('api')->user()->student_id;
         //  $student=Student::findOrFail($student_id);
           $answers_array=[];

           foreach($questions as $question){
             //  return $question;
     $id=Answer::updateOrCreate(['question_id'=>$question['question_id'], 'exam_id'=>$request->exam_id,'student_id'=>$student_id],[
                 'question_id'=>$question['question_id'],
                 'option_id'=>$question['option_id'],
                 'student_id'=>$student_id,
                // 'level_id'=>$student->class_id,
                 'exam_id'=>$request->exam_id,

        ])->id;
             array_push($answers_array,['id'=>$id]);
    }
     $this->cbtResults($request->exam_id);

    return $this->cbtResults($request->exam_id);
}



   public function cbtResults($exam_id,$student_id=null){
                if(empty($student_id)){
       $student_id=auth('api')->user()->student_id;
                }
       $answers=Answer::where([['exam_id',$exam_id],['student_id',auth('api')->user()->student_id]])
       ->select('option_id','question_id')->get();
       foreach($answers as $answer){
           $right=false;
           $question=Question_answer::where([['question_id',$answer->question_id],['exam_id',$exam_id]])->first();
           if($question){
           if($answer->option_id===$question->right_option){
               $right=true;
           }}
              Cbtresult::updateOrCreate(['question_id'=>$answer->question_id,'student_id'=>$student_id, 'exam_id'=>$exam_id],
              ['student_id'=>$student_id,
               'question_id'=>$answer->question_id,
               'exam_id'=>$exam_id,
               'is_right'=>$right
              ]);
       }
       return count(Cbtresult::where([['student_id',$student_id],['exam_id',$exam_id],['is_right',1]])->get());
   }






   public function cbtScores($exam_id){
       $exam=Exam::findOrFail($exam_id);
       $questions=count(Question::where('exam_id',$exam_id)->get());
       $scoreArray=[];
       $score=0;
       $students=DB::table('students')->where([['arm_id',$exam->arm_id],['class_id',$exam->level_id]])->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,students.id as id'))->get();
        foreach($students as $student){
            $answer=count(Cbtresult::where([['student_id',$student->id],['exam_id',$exam_id],['is_right',1]])->get());
            if($answer>0){
                $score=round(($answer/$questions)*100,2);
                array_push($scoreArray,['exam_id'=>$exam->id,'id'=>$student->id,'name'=>$student->name,'score'=>$score]);
                $score=0;
            }
            else{
                array_push($scoreArray,['exam_id'=>$exam->id,'id'=>$student->id,'name'=>$student->name,'score'=>$score]);

            }
        }
       return $scoreArray;
   }



    public function useCBT(Request $request)
   {  $tb_field='';

    if($request->cbt==='exams'){
        $tb_field='exams';
    }
    if($request->cbt==='ca1'){
        $tb_field='test1';
    }
    if($request->cbt==='ca2'){
        $tb_field='test2';
    }
    if($request->cbt==='ca3'){
        $tb_field='test3';
    }
    // return $tb_field;
    $exam=Exam::findOrFail($request->exam_id);
    $questions=count(Question::where('exam_id',$request->exam_id)->get());
      $students=Cbtresult::where('exam_id',$request->exam_id)->select('student_id')->distinct('student_id')->get();
        // return$students;
        $report=Report::findOrFail($request->report_id);
      foreach($students as $student){
       $score=count(Cbtresult::where([['student_id',$student->student_id],['exam_id',$request->exam_id],['is_right',1]])->distinct('student_id')->get());
       $subject_type=Level_sub::where([['level_id',$exam->level_id],['subject_id',$exam->subject_id]])->first();
             $score=round((($score/$questions)*$request->cbt_value),2);

          $mark=Mark::updateOrCreate(['report_id'=>$request->report_id,'subject_id'=>$exam->subject_id,'student_id'=>$student->student_id],[
                  $tb_field=>$score,
                'report_id'=>$request->report_id,
                'arm_id'=>$exam->arm_id,
                'level_id'=>$exam->level_id,
                'student_id'=>$student->student_id,
                 'type'=>$subject_type->type,
                 'term_id'=>$report->term_id

            ]);
             // $mark=Mark::findOrFail($id);
            $mark->total=$this->sum($mark->test1,$mark->test2,$mark->test3,$mark->exams);


             $gradding=$this->grade($mark->total);
             $mark->grade=$gradding['grade'];
             $mark->narration=$gradding['narration'];
            $mark->save();
         }

         Markcheck::create([
            'report_id'=>$request->report_id,
            'subject_id'=>$exam->subject_id,
             'is_history'=>0
        ]);

        CheckResult::create([
         'report_id'=>$request->report_id,
         'is_history'=>0

     ]);
     $exam->is_used=1;
     $exam->save();
       return $exam;
   }



   public function useAssignment(Request $request)
   {  $tb_field='';

    if($request->cbt==='exams'){
        $tb_field='exams';
    }
    if($request->cbt==='ca1'){
        $tb_field='test1';
    }
    if($request->cbt==='ca2'){
        $tb_field='test2';
    }
    if($request->cbt==='ca3'){
        $tb_field='test3';
    }
    // return $tb_field;
    $exam=Assignment::findOrFail($request->id);
    //$questions=count(Question::where('exam_id',$request->exam_id)->get());
      $students=Studentassignment::where('assignment_id',$request->id)->select('student_id','score')->distinct('student_id')->get();
      $report=Report::findOrFail($request->report_id);
      // return$students;
      foreach($students as $student){
       //$score=count(Cbtresult::where([['student_id',$student->student_id],['exam_id',$request->exam_id],['is_right',1]])->distinct('student_id')->get());
             $score=round((($student->score/100)*$request->assignment_value) ,2);
 $subject_type=Level_sub::where([['level_id',$exam->level_id],['subject_id',$exam->subject_id]])->first();
         $mark=Mark::updateOrCreate(['report_id'=>$request->report_id,'subject_id'=>$exam->subject_id,'student_id'=>$student->student_id],[
                  $tb_field=>$score,
                'report_id'=>$request->report_id,
                'arm_id'=>$exam->arm_id,
                'level_id'=>$exam->level_id,
                'student_id'=>$student->student_id,
                 'type'=>$subject_type->type,
                 'term_id'=>$report->term_id

            ]);
             // $mark=Mark::findOrFail($id);
            $mark->total=$this->sum($mark->test1,$mark->test2,$mark->test3,$mark->exams);


             $gradding=$this->grade($mark->total);
             $mark->grade=$gradding['grade'];
             $mark->narration=$gradding['narration'];
            $mark->save();
         }

         Markcheck::create([
            'report_id'=>$request->report_id,
            'subject_id'=>$exam->subject_id,
             'is_history'=>0
        ]);

        CheckResult::create([
         'report_id'=>$request->report_id,
         'is_history'=>0

     ]);
     $exam->is_used=1;
     $exam->save();
       return $exam;
   }
   public function grade($score)
{
    if(is_numeric($score)){
        $score=round($score,2);
    $grading=Grading::where([['lower_bound','<=',$score],['upper_bound','>=',$score],['school_id',auth('api')->user()->school_id]])->first();
    if($grading){
   $Grade=$grading->grade;
   $credite_point=$grading->credit_point;
   $narration=$grading->narration;

  return ["grade"=>$Grade,"narration"=>$narration,'credit_point'=>$credite_point,'total'=>$score];
}}
return ["grade"=>'F',"narration"=>'','credit_point'=>0,'total'=>0];
}


public function sum($t1,$t2,$t3,$exams){
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


        return round($sum,2);
    }


    public function CBT_Review( $exam_id,$student_id=null)
    {
        //return 'here you are';
        if(empty($student_id)){
            $student_id=auth('api')->user()->student_id;
        }
        $student=Student::where('id',$student_id)->with('levels')->first();
        $cbt=[];
          $score=0;
          $isSubmitted=false;
          $exam=Exam::with(['level','subject'])->where('id',$exam_id)->first();
        $questions =Question::where('exam_id',$exam_id)->get();
        foreach($questions as $question){
            $options=Question_option::with('options')->where('question_id',$question->id)->get();
         // return $question->id;
            $answer=Answer::where([['exam_id',$exam_id],['student_id',$student_id],['question_id',$question->id]])->first();
            $is_right=Cbtresult::where([['exam_id',$exam_id],['question_id',$question->id],['student_id',$student_id]])->first();
            $right_option=Question_answer::where([['exam_id',$exam_id],['question_id',$question->id]])->first();

            array_push($cbt,['question'=>['question'=>$question->question,'id'=>$question->id,'is_right'=>$is_right?$is_right->is_right:1],'options'=>$options,'student_answer'=>$answer,'right_option'=>$right_option]);
        }
        $check_submitted=Answer::where([['student_id',$student_id],['exam_id',$exam_id]])->get();
       if(count($check_submitted)>0){
           $score=$this->cbtResults($exam_id,$student_id);
        $isSubmitted=true;
       }

           return[ 'student'=>$student,'cbt'=>$cbt,'exam'=>$exam,'isSubmitted'=>$isSubmitted,'score'=>$score];
    }



}
