<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff_comment extends Model
{
    protected $table="staff_comments";
    protected $fillable=[
        'upper_bound','lower_bound','comment','school_id','level_id','arm_id'
    ];
}
