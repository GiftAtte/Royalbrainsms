<?php
namespace App\Http\Controllers\API\Traits;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Models\Livestream\LiveClass;
use App\Models\Livestream\ZoomOauth;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
trait ZoomApiTrait{

  public function is_table_empty() {
               $data=   ZoomOauth::where('provider','zoom')->first();
         if(empty($data)){
            return true;
         }else{
            return false;
         }


    }

    public function get_access_token() {

        if(!($this->is_table_empty())){
          $data=   ZoomOauth::where('provider','zoom')->first();
             return  $data->provider_value;
        }



    }

    public function get_refersh_token() {
        return $this->get_access_token();
    }

    public function update_access_token($token,$refresh_token) {

        if($this->is_table_empty()) {
          return   ZoomOauth::create([ 'provider'=>'zoom',
                                  'provider_value'=>$token,
                                  'refresh_token'=>$refresh_token,
                                 ]) ;
        } else {
             ZoomOauth::where('provider','zoom')->update([
                 'provider_value'=>$token,
                  'refresh_token'=>$refresh_token,
             ]);
        }
        return ['message'=>'success'];
    }




  public function generateAccessToken(){
     try {


     $url="https://zoom.us/oauth/authorize?response_type=code&client_id=".env('CLIENT_ID')."&redirect_uri=".env('REDIRECT_URL');

        Http::get($url);
        return "Authorize access token generated Success";
} catch(Exception $e) {
    echo $e->getMessage();
}

  }



function create_meeting(Request $request) {
    //$accessToken=$this->get_access_token();
    //$accessToken = $this->getRefershToken();


    $accessToken=Http::withHeaders( ["Content-Type"=>'application/json'])
            ->get('https://portal.royalbrainsms.com/getRefreshToken');

    $meetingData= $this->validateMeeting($request);

     $meetingData['school_id']=AppUtils::getSchoolId();
     $meetingData['created_by']=AppUtils::getCurrentEmployeeId();

    $data=[
                "topic" => $request->title,
                "type" => 2,
                "start_time" => $request->start_time,
                "duration" => '40', // 30 mins
                "password" => $request->meeting_passord
    ];


    try {
      $response = Http::withHeaders( ["Authorization" => "Bearer $accessToken",
                                       "Content-Type"=>'application/json'
                                      ])
                                      ->post('https://api.zoom.us/v2/users/me/meetings',$data);

       // return $response->json();
             if(!empty($response['id'])){
              $meetingData['meeting_id']=$response['id'];
              $meetingData['meeting_password']=$response['password'];
               LiveClass::create($meetingData);
               return [
                    'status'=>'success',
                    'status_code'=>201,
                    'message'=>'meeting successfully created',
                    'meeting'=>$response->json()
               ];
             }
             // return $response->json();
             if($response['code']===124){
               // return $response;
               $this->getRefershToken();
             //  return $this->create_meeting($request);
             }

             else{
                return $response;
             }

            } catch(Exception $e) {
        if( 401 == $e->getCode() ) {
            $this->getRefershToken();
           return $this->create_meeting($request);
        } else {
            echo $e->getMessage();
        }
    }
}




public function validateMeeting(Request $request){

 $meetingData=$request->validate([
'title'=>'required',
'level_id'=>'required',
'arm_id'=>'required',
'remarks'=>'required',
'meeting_date'=>'required',
'start_time'=>'required',
'remarks'=>'required'
        ]);

     return $meetingData;
     }






     public function updateMeeting(Request  $request){
    //$accessToken=$this->get_access_token();
    $accessToken = $this->getExternalToken();
              $this->validateMeeting($request);
             $data=[
                "topic" => $request->title,
                "type" => 2,
                "start_time" => $request->start_time,
                "duration" => '40', // 30 mins
                "password" => $request->meeting_passord
             ];
              $meeting_id=$request->meeting_id;
                           $response = Http::withHeaders( ["Authorization" => "Bearer $accessToken",
                                       "Content-Type"=>'application/json'
                                      ])

                                      ->patch('https://api.zoom.us/v2/meetings/'.$meeting_id,$data);

                 if (204 == $response->getStatusCode()) {

                    LiveClass::where('id',$request->id)->update($request->all());
                  return "Meeting updated successfully.";

                      }
                      if($response['code']===124){
                       return $this->getRefershToken();
                       $this->updateMeeting($request);

                      }
else{
    return $response->json();
}
     }





     public function getRefershToken(){

        try {
        $body['grant_type'] = "refresh_token";
      $body['refresh_token'] = strVal(ZoomOauth::where('provider','zoom')
                                           ->first()->refresh_token);


    $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            "Authorization" => "Basic". base64_encode(env('CLIENT_ID').':'.env('CLIENT_SECRETE'))
        ])
          ->post("https://zoom.us/oauth/token", $body)
           ->json();

     // return [ 'REFRESHED'=>$response->json()];
      $this->update_access_token($response['access_token'],$response['refresh_token']);
    return  $response['access_token'];

   // $this->update_access_token(json_encode($token));
    echo "Access token inserted successfully.";
} catch(Exception $e) {
    echo $e->getMessage();
}
     }



       public function zoomAuthorizeToken(Request $request){
        try {
        $body['grant_type'] = 'authorization_code';
        $body['code'] = $request->code;
       $body['redirect_uri']='https://portal.royalbrainsms.com/zoomoauthredirect';

   $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            "Authorization" => "Basic". base64_encode(env('CLIENT_ID').':'.env('CLIENT_SECRETE'))
        ])
          ->post("https://zoom.us/oauth/token", $body);


           $this->update_access_token($response['access_token'],$response['refresh_token']);

     return redirect('/liveClasses');

   // $this->update_access_token(json_encode($token));
    echo "Access token inserted successfully.";
} catch(Exception $e) {
    return $e->getMessage();
}


       }



   public function zoomAuthorizeTokenRefresh(Request $request){
        try {
        $body['grant_type'] = 'authorization_code';
        $body['code'] = $request->code;
       $body['redirect_uri']='https://portal.royalbrainsms.com/zoomoauthredirect';

   $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            "Authorization" => "Basic". base64_encode(env('CLIENT_ID').':'.env('CLIENT_SECRETE'))
        ])
          ->post("https://zoom.us/oauth/token", $body);


           $this->update_access_token($response['access_token'],$response['refresh_token']);

     return redirect('/liveClasses');

   // $this->update_access_token(json_encode($token));
    echo "Access token inserted successfully.";
} catch(Exception $e) {
    return $e->getMessage();
}


       }




       public function deleteZoomMeeting($id){
                   //$accessToken=$this->get_access_token();
                  $accessToken= $this->getExternalToken();
                    $meeting_id= LiveClass::findOrFail($id)->meeting_id;
                   $response= Http::withHeaders( ["Authorization" => "Bearer $accessToken",
                                       "Content-Type"=>'application/json'
                                      ])

                                      ->delete('https://api.zoom.us/v2/meetings/'.$meeting_id);

                 if (204 == $response->getStatusCode()||$response['code']==3001) {

                    LiveClass::where('meeting_id',$meeting_id)->delete();
                  return "Meeting deleted successfully.";

                      }else{
                        return $response->json();
                      }



     }

 public function getExternalToken(){
    //       $response= ZoomOauth::where('provider','zoom')->first();
    //  return $response->provider_value;






 return Http::withHeaders( ["Content-Type"=>'application/json'])
            ->get('https://portal.royalbrainsms.com/getExternalToken');

}}
