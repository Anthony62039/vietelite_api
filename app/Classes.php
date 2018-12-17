<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    //
    protected $table = "classes";
    protected $fillable = ['id','name','description','class','note','tuition','teacher','time','active_student'];
}
