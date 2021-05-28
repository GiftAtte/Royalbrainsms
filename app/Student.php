<?php

namespace App;
use App\Observers\StudentObserver;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
    'first_name', 'surname', 'middle_name', 'gender', 'dob','class_id','phone','arm_id','contact_adress',
    'nationality','state','school_id','lga','blood_group',
    'home_town',
    'mother_tongue',
    'other_tongue',
    'state_of_resident',
    'lga_of_resident',
    'bustop',
    'former_school',
    'fname',
    'fphone',
    'femail',
    'religion',
    'foccupation',
    'former_school',
    'mname',
    'mphone',
    'memail',
    'moccupation',
    'course',
    'genotype',
    'height',
    'disability',
    'admission_date',
    'admission_level',

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

