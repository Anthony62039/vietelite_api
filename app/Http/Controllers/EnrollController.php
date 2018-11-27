<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Parents;
use App\Enroll;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use LaravelQRCode\Facades\QRCode;
class EnrollController extends Controller
{
    //
    public function store(Request $request)
    {
        //
        $student = new Student();
        $parent = new Parents();
        $student->last_name = $request->last_name;
        $student->first_name = $request->first_name;
        $student->dob = date('Y-m-d', strtotime($request->dob['month']."/".$request->dob['day']."/".$request->dob['year']));

        $parent->name = $request->parentForm['parent_name'];
        $parent->phone_1 = $request->parentForm['parent_phone_1'];        
        $parent->parent_email = $request->parentForm['parent_email'];
        $parent->save();

        $student->parent_id = $parent->id;

        $student->save();

        $file = public_path('qrcode/'.$student->id.".png");
        QRCode::text($student->id)
                  ->setSize(8)
                  ->setMargin(2)->setOutfile($file) 
                  ->png();
        $student->qr_code = $file;
        $student->save();
        foreach ($request->enrollForm as $key => $enroll) {
        	# code...
        	$e = new Enroll();
        	$e->student_id = $student->id;
        	$e->parent_id = $parent->id;
        	$e->subject = $enroll['subject'];
        	$e->class = $enroll['class'];
        	$e->note = $enroll['note'];
        	$e->appointment = date('Y-m-d', strtotime($enroll['appointment']['month']."/".$enroll['appointment']['day']."/".$enroll['appointment']['year']));
        	$e->save();

        }


    }
}
