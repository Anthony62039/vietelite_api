<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
use Illuminate\Http\Request;

// Get list of students
Route::get('students','StudentController@index');

// Get specific student
Route::get('student','StudentController@show');

// Delete a student
Route::delete('student/{id}','StudentController@destroy');

// Update existing student
Route::put('student/{id}','StudentController@update');

// Create new student
Route::post('student','StudentController@store');