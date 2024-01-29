<?php

namespace App\Models\Pos;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
   
    protected $fillable=[
            'created_by',
            'department_id',
            'store_id',
            'product_details',
            'comment',
            'request_status',
            'request_type',
            'payment_mode',
            'vendor',
            'amount',
            'exp_delivery_date',
            'school_id'

    ];

    protected $casts=[
        "product_details"=>"json"
    ];
    protected $appends=['account_code'];

    protected function getAccountCodeAttribute(){
       
           $firstLaterOfDept=strtoupper(substr($this->department->name, 0, 3));
           $str=str_pad($this->id,6,'0',STR_PAD_LEFT);
        return "{$firstLaterOfDept}-{$str}";
    }


    public function createdBy(){
        return$this->belongsTo(User::class,'created_by');
    }

    public function department(){
        return$this->belongsTo(Department::class,'department_id');
    }


    public function store(){
        return$this->belongsTo(Store::class,'store_id');
    }
}
