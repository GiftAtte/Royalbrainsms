<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable=[
'id','title','level_id','arm_id','subject_id',
'staff_id','start_date','start_time','end_date','end_time','school_id','venue','duration'
    ];

    public function examiner()
    {
        return $this->belongsTo('App\Staff', 'staff_id', 'id');
    }
    public function level()
    {
        return $this->belongsTo('App\Level', 'level_id', 'id');
    }
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'subject_id', 'id');
    }
}
