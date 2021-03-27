<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee_description extends Model
{
    protected $table='fee_descriptions';
    protected $fillable=['feegroup_id','description','amount',
    ];

    public function feegroup()
    {
        return $this->belongsTo('App\Fee_group', 'feegroup_id');
    }
}
