<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table="levels";
    protected $fillable=[
        'level_name','staff_id','school_id','is_history','is_promoted'
    ];
    //
    public function subjects(){
        return $this->belongsToMany(Subject::class);
    }


      public function staff(){
        return $this->belongsTo('App\Staff','staff_id','id');

}
}
