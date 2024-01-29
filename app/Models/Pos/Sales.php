<?php

namespace App\Models\Pos;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{

    protected $fillable=[

        'amount',
        'product_details',
        'sales_agent',
        'customer_name',
        'customer_phone',
        'payment_type',
        'invoice_number',
        'discount',
        'total_amount',
        'discounted_amount',
        'school_id'
    ];


public function salesAgent()  {
    return $this->belongsTo(User::class,'sales_agent');
}

    protected $casts = [
        'product_details' => 'json',
    ];


 
}
