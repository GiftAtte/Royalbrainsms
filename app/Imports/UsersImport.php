<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class UsersImport implements ToModel, WithHeadingRow
{

    public $model = User::class;
    public $header = [
        'id' , 'usertype_id', 'name','email', 'password', 'type', 'bio'
    ];
    public $verifyHeader = true;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { return new User([
        //'id' =>$row['id'],
        'student_id'=>$row['student_id'],
        'staff_id'=>$row['staff_id'],
        'name'=>$row['name'],
        'email'=>$row['email'],
        'password'=>Hash::make($row['password']),
        'type'=>$row['type'],
        'bio'=>$row['bio'],
        'photo'=>$row['photo'],
        'full_name'=> $row['name'].$row['email'],
    ]

    ); // We have matching table fields, so we can just do that
        // return new GasStation($row); // If you don't need globalization

}}
