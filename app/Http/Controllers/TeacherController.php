<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Teacher;
class TeacherController extends Controller
{
    /**

 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
public function index(Request $request)
{
    $record = $request->record;
    $keyword = $request->keyword;
    $page = $request->page;
    if ($record == "") {
        $record = 10;
    }
    $sum_row = count(Teacher::all());
    $sum_page = ceil($sum_row / $record);
    if ($page > $sum_page || !is_numeric($page)) {
        $page = 1;
    }
    $all= Teacher::Search($keyword,$record,$page);
    return response()->json(['code' => 1,'message' => 'ket qua','data' => $all],200);
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
    $data = array(
        'name' => $request->name,
        'description' => $request->description,
        'experience' => $request->exp,
        'address' => $request->address,
        'mobile' => $request->mobile,
        'birthdate' => $request->birthDate,
        'certificate' => $request->certificate,
        'created_at' => date("Y-m-d"),
        'gender' => $request->gender,
    );

    $dataTeacher = Teacher::store1($data);
    return response()->json(['code' => 1,'message' => 'Them thanh cong'],200);
}
/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function show($id)
{
    //
}
/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function edit(Request $request)
{
    $id = $request->id;
    if ($id){
        if (Teacher::find($id) == null){
            return response()->json(['code' => 0,'message' => 'khong ton tai hoc sinh nay'],200);
        }else{
            $teacher = Teacher::edit1($id);
            return response()->json(['code' => 1,'data' => $teacher],200);
        }
    }
}
/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request)
{
    $id = $request->id;
    $data = array(
        'name' => $request->name,
        'description' => $request->description,
        'experience' => $request->exp,
        'address' => $request->address,
        'mobile' => $request->mobile,
        'birthdate' => $request->birthDate,
        'certificate' => $request->certifycate,
        'updated_at' => date("Y-m-d"),
    );
    $dataTeacher = Teacher::update1($data,$id);
    return response()->json(['code' => 1,'message' => 'Cap nhat thanh cong'],200);
}
/**
 * Create a function delete teacher
 *
 * @return void
 */
public function deleteTeacher(Request $request){
    $teacher_id = $request->id;
    if ($teacher_id){
        if (Teacher::find($teacher_id) == null){
            return response()->json(['code' => 0,'message' => 'khong ton tai nhan vien nay'],200);
        }else{
            Teacher::deleteTeacher($teacher_id);
            return response()->json(['code' => 1,'message' => 'Xoa thanh cong'],200);
        }
    }
}
}