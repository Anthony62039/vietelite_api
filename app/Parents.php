<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    //
    public $table = "parents";
    protected $fillable = ['id','name','name_2','phone_1','phone_2','parent_email','work','address','parent_email_2'];
    public $timestamps = false;
}
