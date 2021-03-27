<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activation_key extends Model
{
  protected $table ='activation_keys';
  protected $fillable=[
      'report_id','school_id','activation_key'
  ];

  public function report(){
      return $this->belongsTo('App\Report','report_id');
  }

  public function school(){
    return $this->belongsTo('App\School','school_id');
}
}
