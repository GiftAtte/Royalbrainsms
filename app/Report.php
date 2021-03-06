<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $table='reports';
    protected $fillable=['level_id','term_id','session_id','title','term_start','term_end','school_days','next_term',
    'school_id','header','arm_id','type','gradinggroup_id','isCummulative'];

    public function levels()
    {
        return $this->belongsTo('App\Level', 'level_id');
    }
    public function terms()
    {
        return $this->belongsTo('App\Term', 'term_id');
    }
    public function sessions()
    {
        return $this->belongsTo('App\Sessions', 'session_id');
    }
}
