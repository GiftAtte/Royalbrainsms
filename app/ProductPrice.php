<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{protected $table='product_prices';
    protected $fillable=[
            'product_id',
            'cost_price',
            'selling_price',
            'employeeId',
            'school_id'
    ];
}
