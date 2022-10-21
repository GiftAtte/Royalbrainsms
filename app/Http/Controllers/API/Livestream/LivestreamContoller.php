<?php

namespace App\Http\Controllers\API\Livestream;

use App\Http\Controllers\API\Traits\ZoomApiTrait;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\API\Utils\CurrentUser;
use App\Http\Controllers\Controller;
use App\Models\Livestream\LiveClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;




class LivestreamContoller extends Controller
{
   use ZoomApiTrait;

    public function getAll(){

             if(CurrentUser::isFormTutorOrSubjectTeacher()){

             return LiveClass::where('created_by',AppUtils::getCurrentEmployeeId())
                  ->latest()->paginate(10);
             }
              if(CurrentUser::isStudent()){
               $student=CurrentUser::getStudentById(AppUtils::getCurrentStudentId());


              return LiveClass::where('level_id',$student->class_id)
                 ->latest()->paginate(10);
              }
                return LiveClass::where('live_classes.school_id',AppUtils::getSchoolId())
                                 ->join('staff','live_classes.created_by','=','staff.id')
                                 ->selectRaw('concat(staff.surname, " ",staff.first_name) as creator,live_classes.*')
                                 ->latest()->paginate(10);
    }


    public function createLiveClass(Request $request){

 $data=$request->validate([
'title'=>'required',
'meeting_id'=>'required',
'meeting_password'=>'required',
'level_id'=>'required',
'arm_id'=>'required',
'remarks'=>'required',
'meeting_date'=>'required',
'start_time'=>'required',
'end_time'=>'required',
        ]);

     $data['school_id']=AppUtils::getSchoolId();
     $data['created_by']=AppUtils::getCurrentEmployeeId();

       $updateKey=['id'=>$request->id];
   return  LiveClass::updateOrCreate($updateKey,$data);

    }

   public function deleteMeeting($id){


          if(CurrentUser::isAdmin()||CurrentUser::ownsMeeting($id)){
           return   $this->deleteZoomMeeting($id);
          }
   }


   public function getMeetingById($id){
    return LiveClass::findOrFail($id);
   }



public function createMeeting(Request $request){

 $data=$request->validate([
'title'=>'required',
'meeting_id'=>'required',
'meeting_password'=>'required',
'remarks'=>'required',
'meeting_date'=>'required',
'start_time'=>'required',
'end_time'=>'required',
        ]);

     $data['school_id']=AppUtils::getSchoolId();
     $data['created_by']=AppUtils::getCurrentEmployeeId();

       $updateKey=['id'=>$request->id,AppUtils::getCurrentEmployeeId()];
   return  LiveClass::updateOrCreate($updateKey,$data);

    }



public function getZoomToken(Request $request){
      //return $request->all();
        $role=intval($request->role) ;
        $meeting_number=intval($request->meetingNumber) ;
         return $this->signature($request);
  //return  Http::post('http://signature.enoatte.com.ng/meeting/'.$meeting_number.'/'.$role);
}




public function signature(Request $request){
$key = env('ZOOM_SDK_KEY');
        $secret = env('ZOOM_SDK_SECRET');
        $meeting_number = $request->meetingNumber;
        $role = $request->role;
        $token = array(
            "sdkKey" => $key,
            "mn" => $meeting_number,
            "role" => $role,
            "iat" => time(),
            "exp" => time() + 3600, //60 seconds as suggested
            "tokenExp" => time() + 3600,
        );
      $signature=\Firebase\JWT\JWT::encode($token, $secret, 'HS256');
      return ['signature'=>$signature];
    }






}
