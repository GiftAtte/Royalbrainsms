<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    //
    protected $table='assessments';
    protected $fillable=[
        'report_id','level_id','arm_id','grade','student_id','learning_domain_id'
    ];

    public function Ldomain()
    {
        return $this->belongsTo('App\Learning_domain', 'learning_domain_id');
    }

}
