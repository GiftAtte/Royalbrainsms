<?php

use App\Events\DemoEvent;
use App\Mail\SendResults;
use App\Models\Livestream\ZoomOauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

     return redirect()->to('/dashboard');
});




Route::get('/zoomoauthredirect','API\Livestream\LivestreamContoller@zoomAuthorizeToken');









Route::get('/email', function () {
    return new SendResults() ;
});

// Route::post('/register/new',function(){
//   //  return $id;
//     return view('auth.register');
// });


//Auth::routes();
Route::get('/getExternalToken',function(){


          $response= ZoomOauth::where('provider','zoom')->first();
     return $response->provider_value;

});

Route::get('/getRefreshToken', function(){

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
     // $this->update_access_token($response['access_token'],$response['refresh_token']);



       ZoomOauth::where('provider','zoom')->update([
                 'provider_value'=>$response['access_token'],
                  'refresh_token'=>$response['refresh_token'],
             ]);

      return  $response['access_token'];

   // $this->update_access_token(json_encode($token));
    echo "Access token inserted successfully.";
} catch(Exception $e) {
    echo $e->getMessage();
}
     }

);

Route::post('zoomcallback', function($data){
  return $data;
});


// Authentication Routes...
Route::get('login', [
  'as' => 'login',
  'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('login', [
  'as' => '',
  'uses' => 'Auth\LoginController@login'
]);
Route::post('logout', [
  'as' => 'logout',
  'uses' => 'Auth\LoginController@logout'
]);

// Password Reset Routes...
Route::post('password/email', [
  'as' => 'password.email',
  'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
  'as' => 'password.request',
  'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/reset', [
  'as' => 'password.update',
  'uses' => 'Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
  'as' => 'password.reset',
  'uses' => 'Auth\ResetPasswordController@showResetForm'
]);

// Registration Routes...
Route::get('register/{id?}', [
  'as' => 'register',
  'uses' => 'Auth\RegisterController@showRegistrationForm'
]);
Route::post('registerNewUSer', 'Auth\RegisterController@admissionRegistration');
















Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::get('download_page/{id?}', 'HomeController@download');
Route::get('pdfdownload/{id?}/{report_id}/{email?}', 'HomeController@pdfdownload')->name('pdfdownload');
Route::get('masterCsv/{report_id}', 'HomeController@export_master')->name('export_master');
Route::get('masterAndCa/{report_id}', 'HomeController@masterAndCa')->name('export_master_term');
Route::get('/barcode/{id}', 'BiometricController@downloadBarcode');
Route::get('/messages','ChatsController@fetchMessages');
Route::post('/messages','ChatsController@sendMessages');

Route::post('/resultsBulkDownload/{reportId}','homeController@bulkDownload');

Route::get('pdf_download/{report_id}/{student_id?}', 'API\ReportController@pdfDownload');
Route::get('/dashboard', 'HomeController@index')->name('home');
Route::post('/downloads/{id}',function(){
    return view('welcome');
});
//Route::get('/users',[UserController::class,'index']);
Route::get('{path}','HomeController@index')->where( 'path', '.*' );




