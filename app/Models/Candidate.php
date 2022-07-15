<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
   protected $fillable=[
     'first_name', 'surname', 'middle_name', 'gender', 'dob','class_id','phone','arm_id','contact_adress',
    'nationality','state','school_id','lga','blood_group',
   ];
}
