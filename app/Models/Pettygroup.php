<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pettygroup extends Model
{

      protected $table='pettygroups';
    protected $fillable=[
        'pettygroup_id',
        'title',
        'school_id'
    ];
}
