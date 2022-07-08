<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStock extends Model
{
   protected $fillable=[
                'item_id',
                'quantity',
                'school_id',
                'category_id',
                'employee_id'
   ];
}
