<?php

namespace App\Broadcasting;

use App\Has_arm;
use App\Level;
use App\Student;
use App\User;

class Chats
{
    public function join(User $user, Level $level)
    {

        if($user->type==='student'){
            $student=Student::findOrFail($user->student_id);
             if($student->level_id===$level->id){
                 return $user;
             }
         }

        elseif($user->type==='tutor'){
           $hlevel=Has_arm::where('staff_id',$user->staff_id)->first();
            if($hlevel->level_id===$level->id){
                return $user;
            }
        }
return null;
    }
}
