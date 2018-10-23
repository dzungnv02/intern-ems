<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RollCall;
use App\TimeTable;
use DB;

class RollCallController extends Controller
{
	/**
     * hiển thị danh sách học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getListRollCallStudent(Request $request)
    {
        $timetable_id = request('timetable_id');
	    $studentsOfClass = RollCall::getListRollCall($timetable_id);
	    if($studentsOfClass->count()==0){
	        return response()->json(['code'=>0,'message'=>'Lớp không có học sinh nào!'],200);
	    }
	    else{
	        return response()->json(['code'=>1,'data'=>$studentsOfClass],200);
	    }
	}

	/**
     * điểm danh cho học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function rollCallStudent(Request $request){
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$data = array(
            'student_id' => request('student_id'),
            'timetable_id' => request('timetable_id'),
        );
        
        $roll_call = RollCall::updateOrCreate($data,['status' => request('status'),'time_call' => date('H:i', time())]);
        return response()->json(['code'=>1,'message'=>'Them thanh cong!'],200);
	}

	/**
     * update note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function updateNote(Request $request){
		$id = request('id');
		$data = array(
            'note' => request('note'),
        );
        if ($id == "null") {
        	return response()->json(['code'=>0,'message'=>'Bạn cần điểm danh trước!'],200);
        }else{
        	$roll_call = RollCall::updateNote($data,$id);
        	return response()->json(['code'=>1,'message'=>'Cập nhật thành công!'],200);
        }
        
	}
}