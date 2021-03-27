<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable =['message','level_id'];
   public function user()
    {
       return $this->belongsTo(User::class);
    }
}
