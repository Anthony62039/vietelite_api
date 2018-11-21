<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    public $table = 'students';
    protected $fillable = ['id','parent_id','first_name','last_name','dob','gender','school','class','email','phone','avatar','qr_code'];
    public $timestamps = false;
}
