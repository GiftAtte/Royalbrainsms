<?php

namespace App\Http\Controllers\API;

use App\Has_arm;
use App\Staff_comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffcommentController extends Controller
{

   public function __construct()
   {
       $this->middleware('auth:api');
   }

   public function index()
   {
           $user=auth('api')->user();
           if($user->type==='tutor'){
               $level=Has_arm::where('staff_id',$user->staff_id)->first();
            return Staff_comment::where([['school_id',$user->school_id],['level_id',$level->level_id],['arm_id',$level->arms_id]])
            ->latest()->paginate(10);
           }
       return Staff_comment::where('school_id',auth('api')->user()->school_id)
       ->latest()->paginate(10);
   }

   public function store(Request $request)
   {


       $this->validate($request,[
           'lower_bound' => 'required',
           'upper_bound' => 'required',

           'comment' => 'required',

       ]);
       $level=Has_arm::where('staff_id',auth('api')->user()->staff_id)->first();
       return Staff_comment::create([
           'lower_bound' => $request->lower_bound,
           'upper_bound' => $request->upper_bound,
           'comment' => $request->comment,
            'level_id'=>$level->level_id,
            'arm_id'=>$level->arms_id,
           'school_id' => auth('api')->user()->school_id,
       ]);
   }

   public function update(Request $request)
   {
       $this->validate($request,[
           'lower_bound' => 'required',
           'upper_bound' => 'required',
            'comment' => 'required',


       ]);
      $grading=Staff_comment::findOrFail($request->id);
      $grading->update($request->all());
   }


   public function destroy($id)
   {
      $grading=Staff_comment::findOrFail($id);
      $grading->delete();
   }
}
