<?php

namespace App\Models\Pos;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
 
    protected $fillable=['name','created_by','school_id'];
    protected $appends = [
        'creator',
    ];

    public function products()  {
        return $this->hasMany(Product::class);
    }

    public function createdBy(){
   return $this->belongsTo(User::class,'created_by');

    }

    protected function getCreatorAttribute() {
        return "{$this->createdBy->name}";
    }

    
}
