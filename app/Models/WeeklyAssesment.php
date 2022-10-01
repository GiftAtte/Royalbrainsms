<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyAssesment extends Model
{
    protected $table='weeklyassesments';
    protected $fillable=[
        'subject_id','student_id','class_work',
        'test','school_id','report_id','week_id',
        'school_id'
    ];
}
