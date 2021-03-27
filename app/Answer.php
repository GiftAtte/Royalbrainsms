<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $fillable=[
    'student_id','exam_id','question_id','option_id','level_id'
    ];
}
