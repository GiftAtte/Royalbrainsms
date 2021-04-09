<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckResult extends Model
{
    protected $fillable=[
        'report_id','school_id','is_history'
    ];
}
