<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Studentassignment extends Model
{
    public function students(){
        return $this->belongsTo('App\Student','student_id');
    }
}
