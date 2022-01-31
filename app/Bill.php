<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable=['title','feegroup_id','amount','neovastId','school_id'];
}
