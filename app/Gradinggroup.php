<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gradinggroup extends Model
{
    //
    protected $table='gradinggroups';
    protected $fillable=['group_name','school_id'];
}
