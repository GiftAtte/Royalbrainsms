<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question_answer extends Model
{
    protected $fillable=[
        'exam_id','question_id','right_option'
    ];

}
