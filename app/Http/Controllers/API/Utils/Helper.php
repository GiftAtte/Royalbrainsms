<?php


Class Helper {

public static function getCurrentEmployeeId(){
    return auth('api')->user()->employee_id?auth('api')->user()->employee_id:0;
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


