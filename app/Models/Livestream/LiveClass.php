<?php

namespace App\Models\Livestream;

use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
   protected $fillable=[
'title',
'meeting_id',
'meeting_password',
'level_id',
'arm_id',
'remarks',
'meeting_date',
'start_time',
'end_time',
'created_by',
'school_id'
   ];
}
