<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    //
    //protected $guaded=[];
    protected $table="marks";
    protected $fillable=['report_id','student_id','subject_id',
    'test1','test2','test3','exams','total','grade','narration','level_id','term_id','cummulative_narration','cummulative_grade',
    'grand_total','arm_id','credit_point','arm_max_score','arm_min_score','arm_avg_score','type','is_history','compute_summary',
    'report_type','note','weighted_score','session_id','annual_score','annual_average','annual_grade','annual_narration','annual_total','class_subj_position','average',
    'arm_subj_position','annual_position','mid_term','type'];

          public function subjects()
          {
              return $this->belongsTo('App\Subject', 'subject_id', 'id');
          }

          public function arms()
          {
              return $this->belongsTo('App\Arm', 'arm_id', 'id');
          }





    //       public function setWeightedScoreAttribute($value)
    // {
    //     $this->attributes['weighted_score'] = round(($value*0.5),2);
    // }
}



