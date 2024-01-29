<?php

namespace App\Models\Pos;

use App\User;
use Illuminate\Database\Eloquent\Model;

class InventorySetting extends Model
{
 
    protected $fillable=[
      'module',
      'setting_key',
      'setting_value',
      'created_by',
      'school_id'
    ];

    public function createdBy(){
        return$this->belongsTo(User::class,'created_by');
    }
}
