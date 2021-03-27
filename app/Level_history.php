<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level_history extends Model
{
    //
protected $table='level_histories';
    protected $fillable=[
        'student_id','level_id','level_name','arm_id'
    ];
    public function students()
    {
        return $this->belongsTo('App\Student', 'student_id', 'id');
    }
    public function arm()
    {
        return $this->belongsTo('App\Arm', 'arm_id', 'id');
    }
    public function levels()
    {
        return $this->belongsTo('App\Level', 'level_id', 'id');
    }
}
