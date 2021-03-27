<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cbtresult extends Model
{
   protected $fillable=[
    'student_id',
    'question_id',
    'exam_id',
    'is_right'
   ];
}
