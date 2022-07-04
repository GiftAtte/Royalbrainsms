<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itemstock extends Model
{
   protected $fillable=[

                'item_id',
                'quantity',
                'school_id',
                'category_id'
   ];
}
