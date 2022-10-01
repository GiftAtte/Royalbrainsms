<?php

namespace App\Models\Livestream;

use Illuminate\Database\Eloquent\Model;

class ZoomOauth extends Model
{
    protected $table="zoom_oauth";
protected $fillable=[ 'provider','provider_value','school_id'];
}
