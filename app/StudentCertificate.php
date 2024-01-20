<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCertificate extends Model
{
  protected $table='student_certificates';

    protected $fillable=[
        'certificate_id',
        'student_id',
        'school_id',
        'certificate_number',
        'created_by'
    ];


public function student(){
    return $this->belongsTo(Student::class);
}
public function certificate(){
    return $this->belongsTo(Certificate::class);
}
}
