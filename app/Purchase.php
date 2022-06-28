<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
   protected $fillable =[
                'product_id',
                'quantity',
                'supplier_id',
                'total_cost',
                'unit_cost',
                'school_id',
                'purchased_date'
   ];
}
