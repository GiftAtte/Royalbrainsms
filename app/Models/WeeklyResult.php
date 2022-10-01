<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyResult extends Model
{
    protected $table='weeklyresults';
    protected $fillable=[
        'subject_id','student_id','class_work_avg','test_avg','school_id','report_id'
    ];
}
