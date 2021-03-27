<?php

namespace App\Listeners;

use App\User;
use App\Events\studentCreated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateStudentUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  studentCreated  $event
     * @return void
     */
    public function handle(studentCreated $event)
    {

        $photo='';
        $portal_id='stud'.$event->student_id;
    if($event->type==='student'){
      $photo='stud'.$event->student_id.'.png';
    }else{
        $photo='emp'.$event->staff_id.'.png';
    }
$password=$this->generateRandomString();
  echo($portal_id);
User::updateOrCreate(['email'=>$event->email],[
'student_id' =>$event->student_id,
'staff_id'=>$event->staff_id,
'name'=> $event->name,
'email'=> $event->email,
'password'=>Hash::make($password),
'type'=>$event->type,
'photo'=>$photo,
'bio'=>'student',
'portal_id'=>$portal_id,
'school_id'=>$event->school_id,



]);
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
