<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrincipalComent extends Model
{   protected $table="principal_comment";
    protected $fillable=[
        'comment_id','school_id','student_id','report_id'
    ];


     public function comments(){
        return $this->belongsTo('App\Comment','comment_id','id');
        }
}
