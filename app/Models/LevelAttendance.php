<?php

namespace App\Models;

use App\Level;
use Illuminate\Database\Eloquent\Model;
use App\Student;
class LevelAttendance extends Model
{
    protected $table='levelattendance';
    protected $fillable=[ 
        'report_id',
        'level_id',
        'student_id',
        'school_id',
        'attendance_date',
        'isPresent',
        'checkout_time',
        'checkin_time',
        'arm_id'];

        public function students() {
            return $this->belongsTo(Student::class,'student_id');
        }

        public function level() {
            return $this->belongsTo(Level::class,'level_id');
        }
}
