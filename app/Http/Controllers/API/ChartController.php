<?php

namespace App\Http\Controllers\API;
use App\Level_history;
use App\Level;
use App\Level_sub;
use App\Student;
use App\Mark;
use App\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user=auth('api')->user();
        if ($user->type==='admin'||$user->type==='superadmin') {
            return Level::where('school_id',auth('api')->user()->school_id)->get();
        }
        elseif($user->type==='tutor'){
         $staff_id=auth('api')->user()->staff_id;
         return  Level::where('staff_id',$staff_id)->get();
        }
        else{
                $id=auth('api')->user()->student_id;
       $student=Student::findOrFail($id);

        return  Level::where('id',$student->class_id)->get();
        }


    }
    public function load_students($id)
    {
         $user=auth('api')->user();
        if($user->type==='student'){
            $students=Student::where('id',$user->student_id)->get();
            $subjects=Level_sub::with('subjects')->where('level_id',$id)->get();
            return response()->json([
                'subjects'=>$subjects,
                'students'=>$students
            ]);
        }
        if($user->type==='tutor'){
            $level=Level::where('staff_id',$user->staff_id);
            $students=Student::where('class_id',$id)->get();
            $subjects=Level_sub::with('subjects')->where('level_id',$level->id)->get();
            return response()->json([
                'subjects'=>$subjects,
                'students'=>$students
            ]);
        }
       $subjects=Level_sub::with('subjects')->where('level_id',$id)->get();
       $students= Student::where('class_id',$id)->get();


       return response()->json([
        'subjects'=>$subjects,
        'students'=>$students
    ]);
    }

    public function load_chart(Request $request)
    {
       // return $request->all();
        $performance=array();
          $scores=DB::table('marks')->where([['student_id',$request->student_id],['subject_id',$request->subject_id]])
                 ->select('total','report_id')->get();

                     foreach ($scores as $score) {
                        $report=Report::with('terms')->where('id',$score->report_id)->first();

                           array_push($performance,['term'=>$report->terms->name,'score'=>$score->total]);
                     }
                     return $performance;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
