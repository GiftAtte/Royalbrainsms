<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
   protected $fillable=['title','description','created_by','school_id'];

        public function user(){
            return $this->belongsTo(User::class,'created_by');
        }
}
