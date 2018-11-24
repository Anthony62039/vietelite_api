<?php

namespace App\Http\Controllers;
use App\Student;
use App\Parents;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
class StudentController extends Controller
{
    //\
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
        $parent->save();

        $student->parent_id = $parent->id;
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

        $students = Student::select('students.id as student_id','parents.id as parent_id','parent_id','first_name','last_name','dob','gender','school','class','email','phone','avatar','qr_code','name','phone_1','phone_2','parent_email','work','address')->join('parents','parent_id','parents.id');
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
                //echo $filter;
                $filter = str_replace('%', '', $filter);
                $result = $students->whereRaw("concat(first_name,' ',last_name) like '%".$filter."%'")->orWhere('students.id', 'like', '%'.$filter.'%')->orWhere('phone_1', 'like', '%'.$filter.'%')->get()->toArray();
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
    public function edit($id)
    {
        //
        if ($id != null) {
            Student::where('id', $id)->update($request->all());  
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if ($id != null) {
            Student::where('id', $id)->update($request->all());  
        }
    }

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
