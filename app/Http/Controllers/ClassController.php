<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use App\Classes;
class ClassController extends Controller
{
    //
    public function count(){
        return Student::count();
    }
    public function index()
    {
        //
        //return \Response::json(Student::join('parents','parent_id','parents.id')->get());
        return Classes::get();
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
        echo "<pre>";
        print_r($request->toArray());
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
            $product = Classes::find($id);
            $product->delete();    
        }
    }
}
