<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;



Route::get('news','API\PostController@index');
Route::get('news/published','API\PostController@publishedPost');
Route::put('news/{id}','API\PostController@publishPost');
Route::post('news','API\PostController@store');
Route::post('news/update','API\PostController@update');
Route::delete('news/{id}','API\PostController@destroy');







Route::get('livestream','API\Livestream\LivestreamContoller@getAll');
Route::post('livestream','API\Livestream\LivestreamContoller@create_meeting');
Route::delete('livestream/{meetingId}','API\Livestream\LivestreamContoller@deleteMeeting');
Route::post('livestream/zoomZignature','API\Livestream\LivestreamContoller@getZoomToken');
Route::post('livestream/updateToken','API\Livestream\LivestreamContoller@getRefershToken');
Route::get('livestream/{meetingId}','API\Livestream\LivestreamContoller@getMeetingById');
Route::put('livestream','API\Livestream\LivestreamContoller@updateMeeting');
Route::get('getLiveStreamArms/{report_id}', 'API\ReportController@loadArms');



Route::get('/zoomOauthSign',function(){

        $url="https://zoom.us/oauth/authorize?response_type=code&client_id=".env('CLIENT_ID')."&redirect_uri=".env('REDIRECT_URL');
      $response= http::post($url);
    return $url;
      //   if($response){

    //     return http::get('https://portal.royalbrainsms.com/zoomToken');
    //   }

});
Route::get('/zoomoauthredirect', function(){

 try {
        $body['grant_type'] = 'authorization_code';
        $body['code'] = $_GET['code'];
        $body['redirect_uri']=env('ZOOM_REDIRECT_URI');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "Authorization" => "Basic ". base64_encode(env(' ZOOM_SDK_KEY').':'.env(' ZOOM_SDK_SECRET '))
        ])
            ->post("https://zoom.us/oauth/token", $body);

     return   $token = json_decode($response->getBody()->getContents(), true);

   // $this->update_access_token(json_encode($token));
    echo "Access token inserted successfully.";
} catch(Exception $e) {
    echo $e->getMessage();
}


});
