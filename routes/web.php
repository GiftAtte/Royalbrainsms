<?php

use App\Events\DemoEvent;
use App\Mail\SendResults;
use App\Models\Livestream\ZoomOauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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




