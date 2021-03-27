<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee_group extends Model
{
    protected $table='fee_groups';
    protected $fillable=['level_id','term_id','session_id','tittle',
    'school_id','due_date','discount','paystack_key'];
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
    public function paystacks()
    {
        return $this->belongsTo('App\Paystack', 'paystack_key');
    }
}
