<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
   protected $fillable=['api_key','email','school_id','is_active'];
}
