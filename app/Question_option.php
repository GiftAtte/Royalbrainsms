<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question_option extends Model
{
    //
    protected $fillable=[
        'exam_id','question_id','option_id','option_value'
    ];

   public function options()
    {
        return $this->belongsTo('App\Option', 'option_id', 'id');
    }
}
