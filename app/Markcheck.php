<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Markcheck extends Model
{
    //
  protected  $fillable=[
      'report_id','subject_id','is_history','school_id','gradinggroup_id'
  ];
}
