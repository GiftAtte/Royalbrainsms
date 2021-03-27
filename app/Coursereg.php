<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coursereg extends Model
{
    //
    protected $fillable=[
        'student_id','subject_id'
    ];

    public function subject(){
        return $this->belongsTo('App\Subject','subject_id');
    }
    public function students(){
        return $this->belongsTo('App\Student','student_id');
    }
}
