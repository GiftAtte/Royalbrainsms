<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable=['message','user_id','level_id','arm_id','school_id'];
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

}

