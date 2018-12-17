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
Route::group([

    'middleware' => 'jwt.auth',

], function ($router) {
	//Student
    Route::get('students','StudentController@index');
    Route::get('student','StudentController@show');
	Route::delete('student/{id}','StudentController@destroy');
	Route::put('student/{id}','StudentController@update');
	Route::post('student','StudentController@store');
	Route::get('student/count', 'StudentController@count');
	Route::get('import', 'StudentController@import');
	//Enroll
	Route::post('enroll', 'EnrollController@store');
	Route::get('enroll/list', 'EnrollController@list');

	//Classes
	Route::get('classes', 'ClassController@index');
	Route::post('class','ClassController@store');
	Route::put('class/{id}', 'ClassController@edit');
	Route::delete('class/{id}','ClassController@destroy');

});// Get list of students

Route::middleware('jwt.auth')->get('users', function(Request $request) {
    return auth()->user();
});