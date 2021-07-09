<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrechestudentDomain extends Model
{
    protected $fillable=[
        'domain_id','rating_id','report_id','student_id','subdomain_id'
    ];


    public function domains()
    {
        return $this->belongsTo('App\CrecheDomain', 'domain_id', 'id');
    }
    public function ratings()
    {
        return $this->belongsTo('App\CrecheRating', 'rating_id', 'id');
    }
    public function subdomains()
    {
        return $this->belongsTo('App\Crechesubdomain', 'subdomain_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id', 'id');
    }
}
