<?php

namespace App\Models\Pos;

use Illuminate\Database\Eloquent\Model;

class SuspendedSale extends Model
{

    protected $fillable=[

        'amount',
        'product_details',
        'sales_agent',
        'customer_name',
        'customer_phone',
        'payment_type',
        'invoice_number',
        'total_amount',
        'discounted_amount',
        'school_id'
    ];

    protected $casts = [
        'product_details' => 'json',
    ];
}
