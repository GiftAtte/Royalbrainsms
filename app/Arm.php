<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arm extends Model
{ protected $table="arms";
    protected $guarded=[];
    //
    protected $fillable=[
        'name','nike_name','school_id'
    ];
}
