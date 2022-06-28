<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    protected $fillable=[
        'name',
        'description',
        'product_code',
        'category_id',
        'stock',
        'quantity_per_roll',
        'school_id'
        
    ];
  public function category(){

  	return $this->belongsTo('App\Category')->withDefault([
        'id' => 0,
        'name' => 'unknow category',

    ]);
  }

   // realtion with stock

   public function stock(){


     return $this->hasMany('App\Stock');

   }

   // relation with sell details


   public function sell_details(){

        return $this->hasMany('App\SaleDetails');

   }


}
