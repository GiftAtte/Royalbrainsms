<?php

use App\Events\DemoEvent;
use App\Mail\SendResults;
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

     return redirect('dashboard');
});
Route::get('/email', function () {
    return new SendResults() ;
});
Route::resource('/posts', 'PostController');

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::get('invoice', function(){
    return view('invoice');
});
Auth::routes();
Route::get('/messages','ChatsController@fetchMessages');
Route::post('/messages','ChatsController@sendMessages');

Route::get('pdf_download/{report_id}/{student_id?}', 'API\ReportController@pdfDownload');
Route::get('/dashboard', 'HomeController@index')->name('home');
Route::get('{path}','HomeController@index')->where( 'path', '.*' );

