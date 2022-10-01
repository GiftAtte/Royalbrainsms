<?php
namespace App\Http\Controllers\API\Traits;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Student;
use RankingTrait;

trait BirthdayTrait{

     public function getBirthdayStudents(){
        //  return date('d');
            return Student::with('levels')
                   ->whereMonth('dob','=',date('m'))
                   ->whereDay('dob', '=',date('d'))
                   ->where('school_id',AppUtils::getSchoolId())
                   ->get();
     }


     public function birthdayReminder(){
            return Student::with('levels')
                   ->whereMonth('dob',date('m'))
                   ->where('school_id',AppUtils::getSchoolId())
                   ->get();
     }








}
