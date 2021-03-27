<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Has_arm;
use App\Http\Controllers\Controller;
use App\Message;
use App\Student;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

         $user=auth('api')->user();
         if($user->type==='student'){
            $student=Student::findOrFail($user->student_id);
            return Message::with('user')->where('level_id',$student->class_id)->limits(100);
         }

         if($user->type==='tutor'){
           $level=Has_arm::where('staff_id',$user->staff_id);

           return Message::with('user')->where('level_id',$level->level_id)->limits(100);
    }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchMessages()
    {
        return Message::with('user')->get();
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
        $user= auth('api')->user();

     $id=  Message::create([
               'message'=>$request->message,
               'user_id'=>$user->id
       ])->id;
       broadcast(new MessageEvent(Message::with('user')->where('id',$id)->first()));

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
    public function update(Request $request, $id)
    {
        //
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
