<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'student_id',
        'user_id',
        'school_id',
        'total_amount',
        'paid_amount',
        'sell_date',
        'discount_amount',
        'payment_method',
        'payment_status',
    ];
    public function client() {
        return $this->belongsTo('App\Client');
    }
    public function transactions() {
        return $this->hasMany('App\Transaction');
    }
    public function products() {
        return $this->hasMany('App\SoldProduct');
    }
    public function user() {
        return $this->belongsTo('App\User');
    }
}
