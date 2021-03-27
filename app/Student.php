<?php

namespace App;
use App\Observers\StudentObserver;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
    'first_name', 'surname', 'middle_name', 'gender', 'dob','class_id','phone','arm_id','contact_adress',
    'nationality','state','school_id','lga'
];

public function levels(){
return $this->hasOne('App\Level','id','class_id');
}
public function arm(){
return $this->belongsTo('App\Arm','arm_id','id');
}
public function users(){
    return $this->hasOne('App\User');
    }




}

