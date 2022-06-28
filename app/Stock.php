<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
   protected $fillable=[
    'purchase_id',
    'product_id',
    'employee_id',
    'last_sold_quantity',
    'last_added_quantity',
    'available_quantity',
    'update_type',
    'status',
    'school_id'
   ];

    // relation with product
    public function product(){

        return $this->belongsTo('App\Product');
    }


    public function category(){

    	return $this->belongsTo('App\Category');
    }

    // relation with user


    public function user(){

    	return $this->belongsTo('App\User')->withDefault([
          'id' => 0,
          'name' => 'Unknown User'
        ]);
    }

    // realtion with vendor

    public function vendor(){

    	return $this->belongsTo('App\Vendor')->withDefault(
           [

            'id' => 0,
            'name' => 'unknown',
           ]

        );
    }

    // relation with sell_details


    public function sell_details(){

        return $this->hasMany('App\SellDetails','stock_id');
    }
}
