<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelAttendance extends Model
{
    protected $table='levelattendance';
    protected $fillable=[ 'report_id','level_id',
    'student_id','school_id','attendance_date','isPresent','arm_id'];
}
