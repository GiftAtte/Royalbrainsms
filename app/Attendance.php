<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
     protected $fillable=[
         'student_id',
         'report_id',
         'school_days',
         'days_present',
         'days_absent'
     ];
}
