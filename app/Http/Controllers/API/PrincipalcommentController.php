<?php

namespace App\Http\Controllers\API;

use App\Comment;
use App\Http\Controllers\Controller;
use App\PrincipalComent;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrincipalcommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return Comment::where('school_id',auth('api')->user()->school_id)->paginate(50);
    }


     public function getAllComments()
    {
      return Comment::where('school_id',auth('api')->user()->school_id)->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return '';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     return  Comment::create([
            'comment'=>$request->comment,
            'school_id'=>auth('api')->user()->school_id
       ]);
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
      return  Comment::where('id',$id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Comment::where('id',$id)->delete();
       return 'deleted successfully';
    }

public function loadComments(Request $request){
   //return $request->all();
    $report=Report::findOrFail($request->report_id);
    $report_id=$request->report_id;
    return  DB::table('students')->where([['students.class_id',$report->level_id],['students.arm_id',$request->arm_id]])

      ->leftJoin('principal_comment', function($join) use($report_id)
      {
          $join->on('principal_comment.student_id', '=', 'students.id')
          ->where('principal_comment.report_id',$report_id);
      })->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,
       students.id as student_id, principal_comment.comment_id as comment_id

       '))->distinct('students.id')->distinct('report.id')
      ->get();
    }



    public function assignComment(Request $request){
        $school_id=auth('api')->user()->school_id;
        $students=$request->number_of_students;
        $report=Report::findOrFail($request->report_id);
       for ($i=0;$i<$students;++$i){
           PrincipalComent::where([['report_id',$report->id],['student_id',$request->student_id[$i]]])->delete();
           PrincipalComent::create(

           [
           'student_id'=>$request->student_id[$i],
           'report_id'=>$request->report_id,
           'comment_id'=>$request->comment_id[$i],
           'school_id'=>$school_id

           ]);}
}

}

