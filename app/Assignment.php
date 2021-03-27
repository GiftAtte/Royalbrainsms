<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    protected $table='assignments';
    protected $fillabe=[
        'level_id','subject_id','comment','school_id','arm_id','note'
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
