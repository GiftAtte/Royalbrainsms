<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    protected $table="results";
    protected $fillable=[
        'student_id','report_id','total_scores','narration',
        'grade','average_scores','total_students','level_id','progress_status',
        'cummulative_average','arm_position','class_position','arm_id',
        'annual_average','annual_total','annual_grade'

    ];

        public function student()
        {
            return $this->belongsTo('App\Student', 'student_id', 'id');
        }

        public function report()
        {
            return $this->belongsTo('App\Report', 'report_id', 'id');
        }


}
