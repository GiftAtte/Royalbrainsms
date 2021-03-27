<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level_sub extends Model
{
    //
    protected $fillable=['level_id','subject_id','type'];
   public function subjects()
    {
        return $this->belongsTo('App\Subject', 'subject_id');

        # code...
    }
}
