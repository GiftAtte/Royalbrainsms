<?php

use App\Events\DemoEvent;
use App\Mail\SendResults;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\UserController;

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
Route::get('/email', function () {
    return new SendResults() ;
});
Route::resource('/posts', 'PostController');

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::get('download_page/{id?}', 'HomeController@download');
Route::get('pdfdownload/{id?}/{report_id}', 'HomeController@pdfdownload')->name('pdfdownload');
Auth::routes();
Route::get('/messages','ChatsController@fetchMessages');
Route::post('/messages','ChatsController@sendMessages');

Route::get('pdf_download/{report_id}/{student_id?}', 'API\ReportController@pdfDownload');
Route::get('/dashboard', 'HomeController@index')->name('home');
Route::post('/downloads/{id}',function(){
    return view('welcome');
});
//Route::get('/users',[UserController::class,'index']);
Route::get('{path}',[UserController::class,'index'])->where( 'path', '.*' );


