<?php

namespace App\Models\Pos;

use Illuminate\Database\Eloquent\Model;

class UnitPrice extends Model
{
   
    protected $fillable=[
        'product_id',
        'stock_id',
        'unit',
        'price',
        'school_id'
    ];
}
