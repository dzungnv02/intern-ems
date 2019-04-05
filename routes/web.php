<?php
//use App\Classes\ZohoCrmConnect;

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
Auth::routes();

Route::get('/', 'HomeController@index')->middleware('auth');

Route::get('/exam', function(){
		return view('examination/list');
})->middleware('auth');

Route::get('/checkDB', function (){
    dd(DB::connection()->getDatabaseName());
})->middleware('auth');

Route::get('/course', function(){
		return view('course/list');
})->middleware('auth');

Route::get('/class', function(){
	return view('class/list');
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('layouts/dashboard');
});

Route::get('/student', function(){
	return view('student/list');
})->middleware('auth');

Route::get('/student/detail', function(){
	return view('student/form');
})->middleware('auth');

Route::get('timetable', function(){
	return view('class/timetable');
})->middleware('auth');

Route::get('rollcall', function(){
	return view('class/rollcall');
})->middleware('auth');

Route::get('holiday', function(){
	return view('holiday/list');
})->middleware('auth');

Route::get('teacher-list', function(){
    $schedule_types = config('app.teacher_schedule_type');
    return view('teacher/list', ['schedule_types' => $schedule_types]);
})->middleware('auth');

Route::get('teacher-weekly-schedule', function(){
    return view('teacher/weekly-schedule');
})->middleware('auth');

Route::get('staff-list', function(){
    return view('staff/list');
})->middleware('auth');

Route::get('staff-add', function(){
    return view('staff/add');
})->middleware('auth');

Route::get('staff-edit', function(){
    return view('staff/edit');
})->middleware('auth');

Route::get('branch', 'BranchController@index')->middleware('auth');
Route::get('branch/add', 'BranchController@add')->middleware('auth');
Route::get('branch/edit', 'BranchController@edit')->middleware('auth');

Route::get('invoice', 'InvoiceController@index')->middleware('auth');
Route::get('invoice/list', 'InvoiceController@getInvoiceList')->middleware('auth');
Route::get('invoice/student-list', 'InvoiceController@getStudentList')->middleware('auth');
Route::get('invoice/class-list', 'InvoiceController@getClassList')->middleware('auth');
Route::post('invoice/tuition_fee_calculate', 'InvoiceController@tuition_calc')->middleware('auth');
Route::post('invoice/save', 'InvoiceController@save_invoice')->middleware('auth');
Route::get('invoice/print/{id}/{act}', 'InvoiceController@print_invoice')->middleware('auth');
Route::get('invoice/pdf/{id}', 'InvoiceController@send_invoice')->middleware('auth');


Route::get('invoice/print', function(){
    return view('invoice/invoice_print');
})->middleware('auth');

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
