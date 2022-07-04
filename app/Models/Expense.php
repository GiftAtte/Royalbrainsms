<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
   protected $fillable=[
    'purpose',
    'amount',
    'expense_date',
    'school_id',
    'pettygroup_id',
    'employee_id'
   ];
}
