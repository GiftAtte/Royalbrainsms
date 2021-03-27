<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentFees extends Model
{
    protected $table="student_fees";
    protected $fillable = [
       'student_id','feegroup_id','activation_status',
       'transaction_id','reference_id','message','amount',
    ];
}
