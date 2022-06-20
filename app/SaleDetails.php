<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
   protected $fillable=[
    'stock_id',
    'sell_id',
    'product_id',
    'category_id',
    'vendor_id',
    'user_id',
    'selling_dat',
    'customer_id',
    'sold_quantity',
    'buy_price',
    'sold_price',
    'total_buy_price',
    'total_sold_price',
    'discount',
    'discount_type',
    'discount_amount',
   ];
}
