<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teachersubject extends Model
{
  protected $table='teacher_subjects';
  protected $fillable=['level_id','staff_id','school_id','subject_id'];


  public function subjects()
    {
        return $this->belongsTo('App\Subject', 'subject_id');

        # code...
    }
    public function levels()
    {
        return $this->belongsTo('App\Level', 'level_id');

        # code...
    }


}
