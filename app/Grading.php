<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grading extends Model
{
    
    protected $table="gradings";
    protected $fillable=[
        'lower_bound','upper_bound','grade','narration','school_id','progress_status','group_id'
    ];


     public function gradinggroups()
    {
      return $this->belongsTo('App\Gradinggroup','group_id','id');
    }
}
