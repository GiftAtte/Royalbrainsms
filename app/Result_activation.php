<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result_activation extends Model
{
    protected $fillable=[
        'student_id','report_id','activation_status','comment','activation_id'
    ];
}
