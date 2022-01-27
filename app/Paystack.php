<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paystack extends Model
{
    protected $table='paystacks';
    protected $fillable=[
        'paystack_key','school_id','bank','clientId'
    ];
}
