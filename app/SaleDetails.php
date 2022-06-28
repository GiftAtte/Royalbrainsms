<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
   protected $table='sell_details';
   protected $fillable=[
    'transaction_id',
    'sales_id',
    'product_id',
    'unit_price',
    'quantity',
    'amount',
   ];



    
}
