<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config_status extends Model
{
    //
    protected $fillable=[
        'status','result_config_id','school_id'
    ];

      public function resultConfig(){
         return $this->belongsTo('App\Result_config','result_config_id');
      }

}
