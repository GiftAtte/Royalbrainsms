<?php

namespace App\Http\Controllers\API\Utils;

use App\Mark;
use Illuminate\Http\Request;

class AppUtils
{
   public static function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



  public function employee_id (){
        return auth('api')->user()->employee_id?auth('api')->user()->employee_id:0;
     }


     public function school_id (){
        return auth('api')->user()->school_id;
     }

public static function getCurrentEmployeeId(){
    return auth('api')->user()->staff_id?auth('api')->user()->staff_id:5;
}


public static function getCurrentStudentId(){
    return auth('api')->user()->student_id;
}

public static function isClassTeacher(){
    return auth('api')->user()->type==='tutor';
}


public static function getSchoolId(){
    return auth('api')->user()->school_id;
}


public static function getPastTotal($student_id,$report){


      if($report->type==='default-result'||
         $report->type==='default-midterm'||
         $report->type==='diamond'||
         $report->type==='navy-template'){
        return Mark::select('total as annual_score','subject_id','term_id','report_id')
         ->where([['student_id',$student_id],['level_id',$report->level_id]
         ])->whereNotIn('report_type',['mid_term','default-midterm'])->whereNotIn('term_id',[4,3])->distinct(['term_id','subject_id'])->get();
      }else{

        // MADONNA ANNUAL
    return  Mark::whereIn('level_id',[$report->level_id])->where('student_id',$student_id)
              ->whereNotIn('report_type',['mid_term','default-midterm','default-result'])
                 ->select('annual_score','subject_id','term_id','total')->distinct('term_id')->get();


      }

                 //Thinks School Annual







}

}


