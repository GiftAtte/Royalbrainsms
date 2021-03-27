<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{  
    protected $guard=[];
    protected $table='events';
    protected $fillable=[
    'event_name','start_date','end_date','school_id'
];
    //
}
