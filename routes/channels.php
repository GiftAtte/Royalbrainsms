<?php

use App\Has_arm;
use App\Level;
use App\Student;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chats.{id}', function ($user,  $id) {

    if($user->type==='student'){
       $student=Student::findOrFail($user->student_id);
       if($student->class_id==(int)$id){
       return $user;
       }

    }

  if($user->type=='tutor'){
      $level=Has_arm::where('staff_id',$user->staff_id)->first();
        if($level->level_id==(int)$id){
           return $user;
        }
      //return $level->level_id===$level->id;

}
}
);


Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat', function ($user) {

    return $user;
});

