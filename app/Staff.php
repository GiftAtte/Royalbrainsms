<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    //
    protected $fillable = [
        'first_name', 'surname', 'middle_name', 'gender','qualification','phone','email',
 'staff_no','bank','account_number','account_type','school_id','lga','state_of_origin','nationality','contact_address'
    ];

    public function users(){
        
          
                return $this->hasOne('App\User', 'staff_id');
            
        
    }
    
}
