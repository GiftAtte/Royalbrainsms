<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssuedItem extends Model
{
    protected $fillable=[
        'issued_to',
        'issued_date',
        'item_id',
        'quantity',
        'return_date',
        'school_id',
        'note',
        'issued_by',
        'isReturned',
        'category_id',
        'type'
    ];
}
