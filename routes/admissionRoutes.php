<?php

use Illuminate\Support\Facades\Route;

Route::get('personalInfo/{id?}','API\Admission\AdmissionController@profile');
Route::post('admission','API\Admission\AdmissionController@store');
Route::get('candidates','API\Admission\AdmissionController@candidateList');

Route::get('admission/levels','API\Admission\AdminLevelController@index');
Route::post('admission/levels','API\Admission\AdminLevelController@store');
Route::put('admission/levels','API\Admission\AdminLevelController@update');
Route::delete('admission/levels/{id}','API\Admission\AdminLevelController@destroy');
// ATTENDANCE
Route::post('addAttendance','API\StudentController@addAttendance');
Route::post('loadAttendance','API\StudentController@loadStudentAttendance');
Route::post('getAttendanceCount','API\StudentController@getAttendanceCount');
Route::post('attendanceByMonth','API\StudentController@getAttendanceByMonth');

Route::post('attendance/check-in','API\StudentController@checkIn');
Route::get('attendance/checkin-checkout','API\StudentController@getCheckInOut');
Route::post('attendance/checkin-checkout/{attendaceDate}','API\StudentController@getCheckInOutByDate');
Route::post('attendance/check-out','API\StudentController@checkOut');

