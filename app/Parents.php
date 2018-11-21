<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    //
    public $table = "parents";
    protected $fillable = ['id','name','phone_1','phone_2','email','work','address'];
    public $timestamps = false;
}
