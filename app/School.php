<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    protected $fillable=[
        'name','website','email','contact_address','state','country'
        ,'email','phone','logo','short_name','gateway_pk','result_templates'
    ];
}
