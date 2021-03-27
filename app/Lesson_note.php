<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson_note extends Model
{
    protected $table='lesson_notes';
    protected $fillabe=[
        'level_id','subject_id','topic','school_id','arm_id'
    ];
    public function level()
    {
        return $this->belongsTo('App\Level', 'level_id', 'id');
    }
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'subject_id', 'id');
    }
    public function arms()
    {
        return $this->belongsTo('App\Arm', 'arm_id', 'id');
    }
}
