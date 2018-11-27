<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    //
    public $table = 'enrolls';
    protected $fillable = ['id','student_id','parent_id','receiver','subject','class','appointment','showUp','testInform','teacher','receiveTime','teacher','receiveTime','result','resultInform','decision','officalClass','firstDay','inform','note','firstday_showup'];
}
