<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table='school_items';
    
    protected $fillable=[
    'name',
    'school_id',
    'category_id',
   ];
}
