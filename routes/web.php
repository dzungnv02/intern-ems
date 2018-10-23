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

Route::get('/', function () {
    //return view('welcome');
    // $zoho_crm = new ZohoCrmConnect();
    // $records = $zoho_crm->getAllRecords(config('zoho.MODULES.ZOHO_MODULE_LEADS'));
    // var_dump($records[0]);
    return view('layouts/dashboard');
})->middleware('auth');
// exam
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

Route::get('student', function(){
	return view('student/list');
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
    return view('teacher/list');
})->middleware('auth');

Route::get('teacher-add', function(){
    return view('teacher/add');
})->middleware('auth');

Route::get('teacher-edit', function(){
    return view('teacher/edit');
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
Route::get('invoice/student-list', 'InvoiceController@getStudentList')->middleware('auth');
Route::get('invoice/class-list', 'InvoiceController@getClassList')->middleware('auth');
Route::post('invoice/tuition_fee_calculate', 'InvoiceController@tuition_calc')->middleware('auth');
Route::get('invoice/print', function(){
    return view('invoice/invoice_print');
})->middleware('auth');

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
