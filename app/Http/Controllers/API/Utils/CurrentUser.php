<?php

namespace App\Http\Controllers\API\Utils;

use App\Has_arm;
use App\Models\Livestream\LiveClass;
use App\Staff;
use App\Student;

class CurrentUser{



public static function isAdmin(){
    return (auth('api')->user()->type==='admin'||
           auth('api')->user()->type==='superadmin')?true:false;
}

public static function isFormTutor(){
    return auth('api')->user()->type==='tutor';
}

public static function isFormTutorOrSubjectTeacher(){
    return (auth('api')->user()->type==='tutor'||
            auth('api')->user()->type==='subjectTeacher');
}

public static function isStudent(){
    return auth('api')->user()->type==='student';
}

public static function isSubjectTeacher(){
    return auth('api')->user()->type==='subjectTeacher';
}

public static function isParent(){
    return auth('api')->user()->type==='parent';
}



public static function isAdminOrTutor(){
    return (auth('api')->user()->type==='admin'||
           auth('api')->user()->type==='tutor');
}

public static function isStudentOrParent(){
    return (auth('api')->user()->type==='student'||
           auth('api')->user()->type==='parent');
}

public static function getEmployeeById($employeeId){
    return Staff::findOrFail($employeeId);
}


// public static function getEmployeeById(){
//     return Staff::findOrFail($employeeId);
// }


public static function getTutorLevel($employeeId){
    return Has_arm::where('employee_id',$employeeId)->first();

}

public static function getStudentById($studentId){
    return Student::findOrFail($studentId);

}

public static function ownsMeeting($meetingId){
        $meeting= LiveClass::findOrFail($meetingId);
    return $meeting->created_by==AppUtils::getCurrentEmployeeId();

}

}

