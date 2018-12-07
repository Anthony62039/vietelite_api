<?php

namespace App\Http\Controllers;

use App\Student;
use App\Parents;
use App\Enroll;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use LaravelQRCode\Facades\QRCode;

class EnrollController extends Controller
{
    //
    public function store(Request $request){
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
            $e->receiver_id = $enroll['user_id'];
        	$e->save();

        }


    }
    public function list(){
         // Take Option ['all','test','getResult','result','firstday']
        $option = Input::get('option');
        // echo $option;
        $filter = Input::get('filter');
        $sortOrder= Input::get('sortOrder');
        $pageNumber = Input::get('pageNumber');
        $pageSize = Input::get('pageSize');

        $result = [];
        $enrolls = Enroll::select('enrolls.id as id','students.id as student_id','parents.id as parent_id',DB::raw('CONCAT(students.last_name," ",students.first_name) as student_name'),'dob','name','phone_1','phone_2','parent_email','subject','enrolls.class as class','note','appointment','appointment_status','assign','assign_time','result','result_status','offical_class','first_day','first_day_status','active','receiver_id')->join('parents','parent_id','parents.id')->join('students','student_id','students.id');
        if($sortOrder == 'desc'){
            $enrolls->orderBy('student_id', 'DESC');
        }
        $filter = str_replace('%', '', $filter);
        // $enrolls = $enrolls->whereRaw("concat(last_name,' ',first_name) like '%".$filter."%' OR concat(last_name,first_name) like '%".$filter."%'  ")->orWhere('students.id', 'like', '%'.$filter.'%')->orWhere('phone_1', 'like', '%'.$filter.'%')->orWhere('phone_2', 'like', '%'.$filter.'%');
        switch ($option) {
            case 'all':
                # code...

                break;
            case 'test':
                # code...
                // echo "case test";
                $result = $enrolls->where('appointment_status', 'Chưa báo')->orWhere('appointment_status','Đã báo')->get()->toArray();
                break;
            case 'getResult':
                # code...
                echo "getReulst";
                $result = $enrolls->where('appointment_status', 'Đã đến')->where('result', NULL)->get()->toArray();
                break;
            case 'result':
                echo "result ";
                $result = $enrolls->where('result','!=',NULL)->where('offical_class', NULL)->get()->toArray();
                # code...   
                
                break;
            case 'firstday':
                # code...
                 $result = $enrolls->where('offical_class', '!=', NULL)->get()->toArray();
                break;
            
            default:
                # code...
                $result = $enrolls->get()->toArray();
                break;
        }
        
        
        $initialPos = $pageNumber * $pageSize;
        $result = array_slice($result, $initialPos, $initialPos + $pageSize);
        // echo"<pre>";
        // print_r($this->filter($result, $filter));      
        
        return json_encode($this->filter($result, $filter), JSON_UNESCAPED_UNICODE);

    }
    public function filter($array, $needle){
        $result = [];
        if($needle == ""){
            return $array;
        }
        foreach ($array as $key => $value) {
            # code...
            $v = json_encode($value, JSON_UNESCAPED_UNICODE);
            if(strpos($v, $needle) !== false){
                array_push($result, $value);
            }

        }
        return $result;
    }
    

}
