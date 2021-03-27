<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Has_arm extends Model
{  protected $table="has_arms";
   protected $guarded=[];
    //
    
    
     public function staff(){
        return $this->belongsTo('App\Staff','staff_id','id');
}

   public function arms(){
        return $this->belongsTo('App\Arm','arms_id','id');
}

 public function levels(){
        return $this->belongsTo('App\Level','level_id','id');
}}