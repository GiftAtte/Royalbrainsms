<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPurchase extends Model
{
   protected $fillable =[
          'school_id',
           'item_id',
           'quantity',
           'employee_id',


   ];
}
