<?php

namespace App\Models\Pos;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    
    protected $fillable=[
            'product_id',
            'quantity',
            "unit_price",
            'manufacturer',
            'expiring_date',
            'barcode',
            'created_by',
            'store_id',
            'manufacturing_date',
            'school_id'
    ];

protected $casts=[
    'unit_price'=>"json",
    'quantity'=>"json"
];
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function createdBy(){
        return$this->belongsTo(User::class,'created_by');
    }

 
}
