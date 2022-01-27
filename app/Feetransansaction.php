<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feetransansaction extends Model
{
    protected $table = "fee_transactions";
      protected $fillable=['name','transaction_id'];
}
