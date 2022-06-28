<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
   protected $fillable=[
                'name' ,
                'phone' ,
                'address' ,
                'email' ,
                'products' ,
                'school_id' ,
                'bank_details'
   ];
}
