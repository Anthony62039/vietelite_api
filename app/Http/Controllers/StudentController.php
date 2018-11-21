<?php

namespace App\Http\Controllers;
use App\Student;
use App\Parents;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
class StudentController extends Controller
{
    //
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
        $studentId = Input::get('studentId', 'all');
        $filter = Input::get('filter');
        $sortOder= Input::get('sortOrder');
        $pageNumber = Input::get('pageNumber');
        $pageSize = Input::get('pageSize');

        //Index
        
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
