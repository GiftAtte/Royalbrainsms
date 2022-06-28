<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    
    
    
    protected $fillable = [
        'student_id',
        'employee_id',
        'school_id',
        'total_amount',
        'paid_amount',
        'sell_date',
        'discount',
        'products_count',
        'payment_method',
        'payment_status',
        'payment_balance',
        'transaction_id'
    ];
    public function students() {
        return $this->belongsTo('App\Student');
    }
    public function salesDetails() {
        return $this->hasMany('App\SaleDetails','sales_id','id');
    }

    public function employees() {
        return $this->belongsTo('App\Staff','employee_id','id');
    }
}
