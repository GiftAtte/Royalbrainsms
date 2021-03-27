<?php

namespace App\Observers;

use App\Student;
use App\LoginDetail;
use Illuminate\Support\Facades\Hash;
use App\School;
use App\User;
class StudentObserver
{
    /**
     * Handle the student "created" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function created(Student $student)
    {
        $school=School::findOrFail(auth('api')->user()->school_id);

        $portal_id='stud'.$student->id;
        $photo=$portal_id.'.png';
        $name=$student->surname.' '.$student->first_name.' '.$student->middle_name;
        $email=strtolower($portal_id.'@'.$school->short_name.'.com');
        $password=$this->generateRandomString();
User::updateOrCreate(['email'=>$email],[
'student_id' =>$student->id,
'name'=> $name,
'email'=> $email,
'password'=>Hash::make($password),
'type'=>'student',
'photo'=>$photo,
'bio'=>'student',
'portal_id'=>$portal_id,
'school_id'=>$school->id,



]);
LoginDetail::updateOrCreate(['email'=>$email],[
    'name'=>$name,
    'email'=> $email,
    'password'=>$password,
    'school_id'=>$school->id,
    'student_id' =>$student->id,
    'portal_id'=>$portal_id,
    'level_id'=>$student->class_id,
    'arm_id'=>$student->arm_id
   ]);





    }

    /**
     * Handle the student "updated" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function updated(Student $student)
    {
        //
    }

    /**
     * Handle the student "deleted" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function deleted(Student $student)
    {
        //
    }

    /**
     * Handle the student "restored" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function restored(Student $student)
    {
        //
    }

    /**
     * Handle the student "force deleted" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function forceDeleted(Student $student)
    {
        //
    }


    public  function generateRandomString($length = 6) {
        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
