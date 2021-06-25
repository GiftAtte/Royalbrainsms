<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeachersComment extends Model
{
    protected $table="teachers_comments";
    protected $fillable=[
        'comment_id','school_id','level_id','arm_id','student_id','report_id'
    ];


    public function comments(){
        return $this->belongsTo('App\Staff_comment','comment_id','id');
        }
}
