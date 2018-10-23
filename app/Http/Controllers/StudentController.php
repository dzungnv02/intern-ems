<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\StudentClass;
use DB;
use App\Http\Requests;

class StudentController extends Controller
{
    /**
     * Hiển thị danh sách học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
    	$record = $request->record;
        $keyword = $request->keyword;
        $page = $request->page;
        if ($record == "") {
            $record = 10;
        }
        $sum_row = count(Student::all());
        $sum_page = ceil($sum_row / $record);
        if ($page > $sum_page || !is_numeric($page)) {
            $page = 1;
        }
        $all= Student::Search($keyword,$record,$page);
        return response()->json(['code' => 1,'message' => 'ket qua','data' => $all],200);
	}

    /**
     * Xóa học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function deleteStudent(Request $request){
		$student_id = $request->id;
        $studentInClass = StudentClass::where('student_id',$student_id)->get();
		if (count($studentInClass) > 0){
			return response()->json(['code' => 0,'message' => 'Không thể xóa học sinh này!'],200);
		}elseif(Student::find($student_id) == null){
            return response()->json(['code' => 0,'message' => 'Không tồn tại học sinh này!'],200);
		}else{
            Student::deleteStudent($student_id);
            return response()->json(['code' => 1,'message' => 'Xóa thành công!'],200);
        }
	}

    /**
     * Thêm học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addStudent(Request $request){
        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'student_code' => $request->student_code,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'created_at' => date("Y-m-d"),
            'updated_at' => date("Y-m-d"),
        );
        $request->validate([
            'email' => 'required|email|unique:students',
            'name' => 'required',
            'student_code' => 'required|unique:students',
            'address' => 'required',
            'mobile' => 'required|numeric',
            'birthday' => 'required|date',
            'gender' => 'required|numeric',
        ]);
        $dataStudent = Student::store1($data);
        return response()->json(['code' => 1,'message' => 'Them thanh cong'],200);
    }

    /**
     * Edit học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editStudent(Request $request){
        $id = $request->id;
        if ($id){
            if (Student::find($id) == null){
                return response()->json(['code' => 0,'message' => 'khong ton tai hoc sinh nay'],200);
            }else{
                $student = Student::edit($id);
                return response()->json(['code' => 1,'data' => $student],200);
            }
        }
    }

    /**
     * Update thông tin học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStudent(Request $request)
    {   
        $id = $request->id;
        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'student_code' => $request->student_code,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'created_at' => date("Y-m-d"),
            'updated_at' => date("Y-m-d"),
        );
        $validator = $request->validate([
            'email' => 'required|unique:students,email,'.$id,
            'name' => 'required',
            'student_code' => 'required|unique:students,student_code,'.$id,
            'address' => 'required',
            'mobile' => 'required|numeric',
            'birthday' => 'required|date',
            'gender' => 'required|numeric',
        ]);
        
        $dataStudent = Student::update1($data,$id);
        return response()->json(['code' => 1,'message' => 'Cap nhat thanh cong'],200);
    }
    
}