<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pettycash extends Model
{
    protected $fillable=['school_id',
                          'amount',
                          'issued_date',
                          'balance_carried_forward',
                          'current_balance',
                          'pettygroup_id',
                          'employee_id'
                        ];
}
