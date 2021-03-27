<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $table='videos';
    protected $fillable=[
        'title','level_id','author_id','video_path'
    ];

    public function levels(){
        return $this->belongsTo('App\Level','level_id');
    }
    public function author(){
        return $this->belongsTo('App\User','author_id');
    }
}
