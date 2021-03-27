<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    //
    protected $table="sessions";
    protected $fillable=['name','school_id'];
}
