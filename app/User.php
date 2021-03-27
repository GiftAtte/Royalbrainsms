<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id','name', 'email', 'password', 'bio', 'type','photo','portal_id','staff_id','school_id'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function students(){
        return $this->belongsTo('App\Student','student_id');
        }

     public function employees(){
            return $this->belongsTo('App\Staff','staff_id');
         }
           public function school(){
            return $this->belongsTo('App\School','school_id');
         }

         public function comments(){
            return $this->hasMany('App\Postcomment');
        }
        public function posts(){
            return $this->hasMany('App\Post');
        }
        public function messages()
        {
            return $this->hasMany(Message::class);
        }
}
