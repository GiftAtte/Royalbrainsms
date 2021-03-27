<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postcomment extends Model
{
   protected $fillable=[
       'body','school_id',
   ];
}
