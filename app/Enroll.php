<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    //
    public $table = 'enrolls';
    protected $fillable = ['id','student_id','parent_id','subject','class','note','appointment','appointment_status','assign','assign_time','result','result_status','offical_class','first_day','first_day_status','active','receiver_id'];
}
