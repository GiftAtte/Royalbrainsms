<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table="comments";
    protected $fillable=[
        'upper_bound','lower_bound','comment','school_id','group_id'
    ];



    public function gradinggroup(){
        return $this->belongsTo('App\Gradinggroup','group_id','id');
}
}
