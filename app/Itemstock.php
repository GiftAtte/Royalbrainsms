<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itemstock extends Model
{
    protected $table='item_stocks';
   protected $fillable=[
                'item_id',
                'quantity',
                'school_id',
                'category_id',
                'employee_id'
   ];
}
