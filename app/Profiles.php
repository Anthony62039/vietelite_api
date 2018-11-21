<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
    public $table = "profiles";
    protected $fillable = ['id','lastname','firstname','img'];
    public $timestamps = false;
}
