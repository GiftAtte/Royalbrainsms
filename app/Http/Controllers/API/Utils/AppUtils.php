<?php

namespace App\Http\Controllers\API\Utils;

use Illuminate\Http\Request;

class AppUtils
{
   public function generateRandomString($length = 6) {
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
    return auth('api')->user()->employee_id?auth('api')->user()->employee_id:5;
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
}


