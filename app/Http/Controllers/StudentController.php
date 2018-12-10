<?php

namespace App\Http\Controllers;
use App\Student;
use App\Parents;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use LaravelQRCode\Facades\QRCode;
class StudentController extends Controller
{
    //\
    function csvToArray($filename = '', $delimiter = '|')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function import(){
        $file = public_path('student.csv');

        $customerArr = $this->csvToArray($file);

        // echo "<pre>";
        // print_r();
        for ($i = 0; $i < count($customerArr); $i ++)
        {
            //print_r($customerArr[$i]['dob']);
            $customerArr[$i]['dob'] = str_replace('/', '-', $customerArr[$i]['dob']);
            // print_r(date('Y-m-d', strtotime($customerArr[$i]['dob'])));
            // echo "<br>";
            $student = new Student();
            $parent = new Parents();
            $student->id = $customerArr[$i]['id'];
            $student->last_name = $customerArr[$i]['last_name'];
            $student->first_name = $customerArr[$i]['first_name'];
            $student->phone = $customerArr[$i]['phone']; 
            $student->email = $customerArr[$i]['email']; 
            $student->dob = date('Y-m-d', strtotime($customerArr[$i]['dob']));

            $parent->name = $customerArr[$i]['parent_name'];
            $parent->phone_1 = $customerArr[$i]['phone_1'];
            $parent->phone_2 = $customerArr[$i]['phone_2'];
            $parent->parent_email =  $customerArr[$i]['email_1'];
            $parent->parent_email_2 =  $customerArr[$i]['email_2'];
            $parent->save();

            $student->parent_id = $parent->id;

            $student->save();

            $file = public_path('qrcode/'.$student->id.".png");
            QRCode::url('qly.vietelite.edu.vn/student/'.$student->id)
                      ->setSize(8)
                      ->setMargin(2)->setOutfile($file) 
                      ->png();
            $student->qr_code = $file;
            $student->save();

        }

        // return 'Jobi done or what ever';    
    }
    public function count(){
        return Student::count();
    }
    public function index()
    {
        //
        //return \Response::json(Student::join('parents','parent_id','parents.id')->get());
        return Student::join('parents','parent_id','parents.id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $student = new Student();
        $parent = new Parents();
        $student->last_name = $request->last_name;
        $student->first_name = $request->first_name;
        $student->phone = $request->phone; 
        $student->email = $request->email; 
        $student->dob = date('Y-m-d', strtotime($request->dob['month']."/".$request->dob['day']."/".$request->dob['year']));

        $parent->name = $request->parentForm['parent_name'];
        $parent->phone_1 = $request->parentForm['parent_phone_1'];
        $parent->phone_2 = $request->parentForm['parent_phone_2'];
        $parent->parent_email = $request->parentForm['parent_email'];
        $parent->name_2 = $request->parentForm['parent_name_2'];
        $parent->parent_email_2 = $request->parentForm['parent_email_2'];
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Take Params
        $studentId = Input::get('studentId');
        $filter = Input::get('filter');
        $sortOrder= Input::get('sortOrder');
        $pageNumber = Input::get('pageNumber');
        $pageSize = Input::get('pageSize');

        $students = Student::select('students.id as student_id','parents.id as parent_id','parent_id','first_name','last_name','dob','gender','school','class','email','phone','avatar','qr_code','name','phone_1','phone_2','parent_email','parent_email_2','work','address')->join('parents','parent_id','parents.id');
        //Index
        //echo $studentId;
        if($sortOrder == 'desc'){
            $students->orderBy('student_id', 'DESC');
        }
        $result = [];

        if($studentId == '-1'){
            if($filter == ''){
                $result =  $students->get()->toArray();
            }
            else{
                //echo "concat(last_name,' ',first_name) like '%".$filter."%'";
                $filter = str_replace('%', '', $filter);
                $result = $students->whereRaw("concat(last_name,' ',first_name) like '%".$filter."%' OR concat(last_name,first_name) like '%".$filter."%'  ")->orWhere('students.id', 'like', '%'.$filter.'%')->orWhere('phone_1', 'like', '%'.$filter.'%')->get()->toArray();
            }
            //echo $filter;
            
        }
        else{            
            $result = Student::join('parents','parent_id','parents.id')->where('students.id',$studentId)->get()->toArray();
        }
        
        $initialPos = $pageNumber * $pageSize;
        $result = array_slice($result, $initialPos, $initialPos + $pageSize);
        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //
        if ($id != null) {
            $student = Student::find($id);
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->gender = $request->gender;
            $student->dob = date('Y-m-d', strtotime("+1 day", strtotime($request->dob)));
            $student->class = $request->class;
            $student->phone = $request->phone;
            $student->school = $request->school;
            $student->save();
            Parents::where('id', $student->parent_id)->update($request->parentForm);
        }
        $students = Student::select('students.id as student_id','parents.id as parent_id','parent_id','first_name','last_name','dob','gender','school','class','email','phone','avatar','qr_code','name','phone_1','phone_2','parent_email','parent_email_2','work','address')->join('parents','parent_id','parents.id')->where('students.id', $id)->get()->toArray();
        return $students;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
         if ($id != null) {
            $product = Student::find($id);
            $product->delete();    
        }
    }
}
