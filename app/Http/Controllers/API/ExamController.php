<?php

namespace App\Http\Controllers\API;

use App\Exam;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Has_arm;
use App\Option;
use App\Question;
use App\Question_answer;
use App\Question_option;
use App\Student;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user=auth('api')->user();
         if($user->type==='student'){
             $student=Student::findOrFail($user->student_id);
        return Exam::with(['examiner','level','subject'])->where([['level_id',$student->class_id],['arm_id',$student->arm_id]])->latest()->paginate(20);
        }
        if($user->type==='tutor'){
            $level=Has_arm::where('staff_id',$user->staff_id)->first();
            return Exam::with(['examiner','level','subject'])->where([['level_id',$level->level_id],['arm_id',$level->arms_id]])->latest()->paginate(20);
        }
        return Exam::with(['examiner','level','subject'])->where('school_id',$user->school_id)->latest()->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
 public function Questions($id)
{       return Question::where('exam_id',$id)->latest()->paginate(20);
    # code...
}

    public function Question($id)
    {
        $question= Question::findOrFail($id);
        $options=Question_option::with('options')->where('question_id',$id)->get();

     $answer=Question_answer::where('question_id',$id)->first();
     $answer=Question_option::with('options')->where([['option_id',$answer->right_option],['question_id',$id]])->first();

        return ['question'=>$question,'options'=>$options,'answer'=>$answer];

                }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { // return $request->all();
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'subject_id' => 'required|integer',
            'level_id' => 'required|integer',
            'arm_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'venue' => 'required|string|max:191',
            //'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,

        ]);
       $user= auth('api')->user();
        return Exam::updateOrCreate(
            ['id'=>$request->id,'subject_id'=>$request->subject_id],
             ['title'=>$request->title,
             'subject_id'=>$request->subject_id,
             'level_id'=>$request->level_id,
             'arm_id'=>$request->arm_id,
             'start_date'=>$request->start_date,
             'end_date'=>$request->end_date,
             'staff_id'=>$user->staff_id,
             'school_id'=>$user->school_id,
             'venue'=>$request->venue,
             'duration'=>$request->duration

        ])->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Exam::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'subject_id' => 'required|integer',
            'level_id' => 'required|integer',
            'arm_id' => 'required|integer',
            'start_date' => 'required',
           // 'end_date' => 'required',

            'venue' => 'required|string|max:191',
            //'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,

        ]);
        $exam=Exam::findOrFail($request->id);

        $exam->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $exam= Exam::findOrFail($id);
       $exam->delete();
    }

    public function deleteQuestion($id)
    {   Question::whereIn('id',[$id])->delete();
        Question_option::whereIn('question_id',[$id])->delete();
       Question_answer::whereIn('question_id',[$id])->delete();


    }


 public function getArms($level_id)
 {

     return Has_arm::with('arms')->where('level_id',$level_id)->get();

 }


 public function loadExams(){
     return Exam::where('school_id',auth('api')->user()->school_id)->get();
 }
 public function examOptions(){
    return Option::all();
}

public function questionOptions(Request $request){
    //return $request->all();
   $exam_option=$request->option_id;
    $question_id=Question::updateOrCreate(['id'=>$request->id],[
             'question'=>$request->question,
             'exam_id'=>$request->exam_id,
             'marks'=>$request->marks
    ])->id;
       foreach($exam_option as $e_option){
            Question_option::updateOrcreate(['question_id'=>$question_id,'option_id'=>$e_option['option_id'],],[
                'exam_id'=>$request->exam_id,
                'question_id'=>$question_id,
                'option_id'=>$e_option['option_id'],
                'option_value'=>$e_option['option']
            ]);
       }

       Question_answer::updateOrInsert(['question_id'=>$question_id],[
           'exam_id'=>$request->exam_id,
           'question_id'=>$question_id,
           'right_option'=>$request->right_option,

       ]);

  return $question_id;
}

}
