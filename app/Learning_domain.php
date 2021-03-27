<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learning_domain extends Model
{
    protected $fillable=[
        "type","domain",'school_id'
    ];
}
