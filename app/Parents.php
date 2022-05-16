<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    protected $fillable=[
        'name','school_id','phoneNumber','contactAddress','email','occupation'
    ];
    public function users(){
    return $this->hasOne('App\User','parent_id','id');
    }
}
