<?php

namespace App\Http\Controllers\API;

use App\Events\LevelChats;
use App\Events\MessageEvent;
use App\Has_arm;
use App\Http\Controllers\Controller;
use App\Level;
use App\Message;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function fetchLevelMessage()
    {

         $user=auth('api')->user();
         if($user->type==='student'){
            $student=Student::findOrFail($user->student_id);
            return Message::with('user')->where('level_id',$student->class_id)->limit(100)->get();
         }

         if($user->type==='tutor'){
           $level=Has_arm::where('staff_id',$user->staff_id)->first();

           return Message::with('user')->where('level_id',$level->level_id)->limit(100)->get();
    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchMessages()
    {
        return Message::with('user')->where('school_id',auth('api')->user()->school_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendMessages(Request $request)
    {
        $message = auth('api')->user()->messages()->create([
            'message' => $request->message,
            'school_id'=>auth('api')->user()->school_id
        ]);

        Broadcast(new MessageEvent($message->load('user')))->toOthers();

        return ['status' => 'success'];
    }

    public function sendLevelMessages(Request $request)
    {
            $user=auth('api')->user();
            $level=null;
            $arm=null;
            if($user->type==='student'){
                $student=Student::findOrFail($user->student_id);
                $level=$student->class_id;
                $arm=$student->arm_id;
            }
            if($user->type==='tutor'){
                $C_level= Has_arm::where('staff_id',$user->staff_id)->first();
               $level=$C_level->level_id;
               $arm=$C_level->arms_id;
            }
      $message =Message::create([
            'message' => $request->message,
            'level_id'=>$level,
            'arm_id'=>$arm,
            'user_id'=>$user->id,
            'school_id'=>auth('api')->user()->school_id

        ]);

        Broadcast(new LevelChats($message->load('user')))->toOthers();

        return ['status' => 'success'];
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
    public function getLevel()
    {

        $user=auth('api')->user();
        if($user->type==='student'){
           $student=Student::findOrFail($user->student_id);
           return Level::findOrFail($student->class_id);
        }

        if($user->type==='tutor'){
          $level=Has_arm::where('staff_id',$user->staff_id)->first();

          return Level::findOrFail($level->level_id);
               }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
