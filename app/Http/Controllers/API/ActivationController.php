<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Report;
use App\Result;
use App\Student;
use App\Mark;
use App\Result_activation;
use App\Level_history;
class ActivationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function loadActivation($report_id,$arm_id)
    {
      $report=Report::findOrFail($report_id);
       $studentsA=[];
     $students=Mark::where([['report_id',$report_id],['arm_id',$arm_id]])->distinct('student_id')->pluck('student_id')
     ->toArray();


    //      $history=Level_history::where([['level_id',$report->level_id],['arm_id',$arm_id]])->pluck('student_id')->toArray();
    //       $studentsA=array_merge($students,$history);
    //       if(!empty($history)){
    //   $studentsMerge=array_merge($students,$history);
       return  \DB::table('students')->whereIn('students.id',$students)
        ->leftJoin('result_activations', function($join) use($report_id)
        {
            $join->on('result_activations.student_id', '=', 'students.id')
            ->where('result_activations.report_id',$report_id);
        })->select(\DB::raw('CONCAT(students.surname," ", students.first_name)as name,
         students.id as student_id, result_activations.activation_status as activation_status,
         result_activations.comment as comment
         '))->distinct('students.id')->orderBy('name')
        ->get();






    //   return  \DB::table('students')->where([['class_id',$report->level_id],['arm_id',$arm_id]])
    //     ->leftJoin('result_activations', function($join) use($report_id)
    //     {
    //         $join->on('result_activations.student_id', '=', 'students.id')
    //         ->where('result_activations.report_id',$report_id);
    //     })->select(\DB::raw('CONCAT(students.surname," ", students.first_name)as name,
    //      students.id as student_id, result_activations.activation_status as activation_status,
    //      result_activations.comment as comment
    //      '))->distinct('students.id')->orderBy('name')
    //     ->get();

    }
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
      $students=$request->number_of_students;
          //return $request->all();
        for ($i=0;$i<$students;++$i){
       \DB::table('result_activations')->updateOrInsert(
            ['report_id' => $request->report_id, 'student_id' => $request->student_id[$i]],[
            'student_id'=>$request->student_id[$i],
            'report_id'=>$request->report_id,
            'activation_status'=>$request->activation_status[$i],



        ]);

    }}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivateResults(Request $request)
    {
        $students=$request->csv;
        foreach($students as $student){
            \DB::table('result_activations')->updateOrInsert(
                 ['report_id' => $student['report_id'], 'student_id' => $student['students_id']],[
                 'student_id'=>$student['students_id'],
                 'report_id'=>$student['report_id'],
                 'activation_status'=>0,



             ]);

         }}
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
    public function importProgress(Request $request)
    {
        $students=$request->csv;
          foreach($students as $student){

                Result::where([['student_id',$student['student_id']],['report_id', $student['report_id']]])
              ->update([

                    'progress_status' => $student['progress_status'],

                ]);



    }
    }




}

