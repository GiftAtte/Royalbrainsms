<?php
use Illuminate\Support\Facades\Route;


Route::post('loadWeeklyAssesment','API\ScoreController@loadWeeklyAssesment');
Route::post('weeklyScores','API\ScoreController@updateWeeklyScores');
Route::post('deleteWeeklyActivity','API\ScoreController@deleteWeeklyActivity');
