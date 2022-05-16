<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginDetail extends Model
{
    //
    protected $fillable=[
        'name','email','password','student_id','staff_id','school_id'
        ,'level_id','portal_id','arm_id','parent_id'
    ];

    public function arms(){
        return $this->belongsTo('App\Arm','arm_id');
    }
}
