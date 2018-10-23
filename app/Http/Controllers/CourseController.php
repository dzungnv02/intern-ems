<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Requests\CourseRequest;
use App\Course;
use Validator;

class CourseController extends Controller
{
    /**
     * Lấy danh sách khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getListCourse(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('rec');
        
        if($recordsPerPage==""){
            $recordsPerPage =10;
        }
        $resultAfterSearch= Course::getResultSearch($keyword,$recordsPerPage);

        if($resultAfterSearch->count()==0){
            return response()->json(['code'=>0,'message'=>'Không tìm thấy khóa học!'],200);
        }
        else{
            return response()->json(['code'=>1,'data'=>$resultAfterSearch],200);
        }

    }

    /**
     * Xóa khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function deleteCourse(Request $request)
    {
        $idCourse = $request->id;
        $findIdCourse = Course::getInfoCourse($idCourse);
        $checkClass = Course::checkClass($idCourse);
        
        if($findIdCourse==""){
            return response()->json([ "code"=>0,"message"=>"Không tìm thấy khóa học cần xóa!"],200);
        }else if($checkClass){
            return response()->json([ "code"=>0,"message"=>"Khóa học này đang có lớp "],200);
        }
        else{
            Course::deleteCourse($idCourse);
            return response()->json(['code'=>1,'message'=>'Xóa khóa học thành công!']);
        }
    }

    /**
     * Tạo mới khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function createCourse(Request $request)
    {
        $errors = Validator::make($request->all(),
            [
                        'name'      =>'required',
                        'code'      =>'required|unique:courses',
                        'duration'  =>'required|numeric',
                        'fee'       =>'required|numeric',
                        'curriculum'=>'required',
                        'level'     =>'required'
            ],
            [
                        'name.required'         =>"Tên khóa học không được trống!",
                        'code.required'         =>"Mã khóa học không được trống!",
                        'code.unique'           =>"Mã khóa học đã tồn tại! ",
                        'duration.required'     =>"Thời lượng không được trống!",
                        'fee.required'          =>"Học phí không được trống!",
                        'curriculum.required'   =>"Gíao trình không được trống!",
                        'level.required'        =>"Trình độ không được trống!",
                        'fee.numeric'           =>"Học phí phải là kiểu số",
                        'duration.numeric'      =>"Thời lượng phải là kiểu số"
            ]
        );
        if($errors->fails()){
            $arrayErrors = $errors->errors()->all();
            $message = [ "code"=>0,"message" => $arrayErrors];
            return response()->json($message,200);
        }else{
            Course::createCourse($request);
            return response()->json(['code'=>1,'message'=>'Thêm khóa học thành công!']);
        }

    }

    /**
     * Lấy thông tin khóa học cần sửa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getEditCourse(Request $request){
        $idCourse = $request->input('course_id');
        $infoCourseNeedEdit = Course::getInfoCourse($idCourse);
        return $infoCourseNeedEdit;
    }

    /**
     * Lấy thông tin lớp khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getInfoCourse(Request $request)
    {
        $infoCourse = Course::getInfoCourse($request->id);
        if($infoCourse)
            return $infoCourse;
        else
            return response()->json(["code"=>0,"message"=>"Không tìm thấy khóa học cần xóa "]);
    }

    /**
     * Chỉnh sửa khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function editCourse(Request $request)
    {
        $id= $request->course_id;
        $errors = Validator::make($request->all(),
            [
                        'name'      =>'required',
                        'code'      =>'required|unique:courses,code,'.$id,
                        'duration'  =>'required|numeric',
                        'fee'       =>'required|numeric',
                        'curriculum'=>'required',
                        'level'     =>'required'
            ],
            [
                        'name.required'         =>"Tên khóa học không được trống!",
                        'code.required'         =>"Mã khóa học không được trống!",
                        'code.unique'           =>"Mã khóa học đã tồn tại! ",
                        'duration.required'     =>"Thời lượng không được trống!",
                        'fee.required'          =>"Học phí không được trống!",
                        'curriculum.required'   =>"Gíao trình không được trống!",
                        'level.required'        =>"Trình độ không được trống!",
                        'fee.numeric'           =>"Học phí phải là kiểu số",
                        'duration.numeric'      =>"Thời lượng phải là kiểu số"
            ]
        );
        if( $errors->fails()){
            $arrayErrors = $errors->errors()->all();
            $message = [ "code"=>0,"message"=> $arrayErrors];
            return response()->json($message,200);
        }else{
            Course::editCourse($id,$request);
            return response()->json(['code'=>1,'message'=>'Chỉnh sửa khóa học thành công!']);
        }
    }
}