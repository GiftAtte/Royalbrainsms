<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crechesubdomain extends Model
{
    protected $fillable=[
        'name','school_id','domain_id'
    ];

    public function domains()
    {
        return $this->belongsTo('App\CrecheDomain', 'domain_id', 'id');
    }
}
