<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Examination;
use Validator;

class ExaminationController extends Controller
{
    /**
     * Danh sách kì thi 
     *
     * @param  array  $Request
     * @return void
     */

    public function index(Request $request){
        $classid = $request->classid;
    	$record = $request->record;
        $keyword = $request->keyword;
        $page = $request->page;
        if ($record == "") {
            $record = 10;
        }
        $sum_row = count(Examination::all());
        $sum_page = ceil($sum_row / $record);
        if ($page > $sum_page || !is_numeric($page)) {
            $page = 1;
        }
        $data= Examination::Search($keyword,$record,$page,$classid);
        return response()->json(['code' => 1,'data' => $data],200);
    }
    /**
     * Xóa kì thi 
     *
     * @param  array  $Request
     * @return void
     */
    public function deleteExam(Request $request){
        $id = $request->id;
        $errors = Validator::make($request->all(),
            [
                'id'        => 'required|numeric',
            ],
            [
                'id.required'           =>"Không tồn tại id",
                'id.numeric'           =>"Id không phải là sô",
            ]
        );
        $data = Examination::editExam($id);
        if($errors->fails() || $data['start_day'] < date('Y-m-d H:i:s') ){
                $arrayErrors = $errors->errors();
                $message = [ "code"=>0,"validate"=>$arrayErrors,'message'=>'Không thể xóa kì thi'];
                return response()->json($message,200);
            }else{
                $delete = Examination::deleteExam($id);
                return response()->json(['code'=>1,'message' => 'xoa thanh cong']);
            }
    }
    /**
     * Thêm kì thi 
     *
     * @param  array  $Request
     * @return void
     */
    public function  createExam(Request $request){
        $errors = Validator::make($request->all(),
        [
                    'name'      =>'required',
                    'start_day' =>'required|date',
                    'duration'  =>'required|numeric',
                    'note'      =>'required',
                    'class_id'  =>'required|numeric',
        ],
        [
                    'name.required'         =>"Không tồn tại name",
                    'start_day.required'    =>"Không tồn tại start_day",
                    'start_day.date'        =>"start_day không đúng định dạng",
                    'duration.required'     =>"Không tồn tại duration",
                    'duration.numeric'      =>"duration không phải là sô",
                    'note.required'         =>"Không tồn tại ghi chú",
                    'class_id.numeric'      =>"class id không phải là sô",
                    'class_id.required'     =>"Không tồn tại class id",

        ]
    );

    if($errors->fails()||$request->start_day < date('Y-m-d H:i:s')){
        $arrayErrors = $errors->errors();
        $message = [ "code"=>0,"message"=>$arrayErrors];
        return response()->json($message,200);
    }else{
        $data = Examination::createExam($request);
        
        return response()->json(['code'=>1,'message'=>'Thêm kì thi thành công!']);
    }
        }
    /**
     * update kì thi 
     *
     * @param  array  $Request
     * @return void
     */
    public function  updateExam(Request $request){
        $errors = Validator::make($request->all(),
            [
                        'id'        => 'required',
                        'name'      =>'required',
                        'start_day' =>'required|date',
                        'duration'  =>'required|numeric',
                        'note'      =>'required',
                        'class_id'  =>'required',
            ],
            [
                        'id.required'           =>"Không tồn tại id",
                        'name.required'         =>"Không tồn tại name",
                        'start_day.required'   =>"Không tồn tại start_day",
                        'start_day.date'        =>"start day không đúng định dạng",
                        'duration.required'     =>"Không tồn tại duration",
                        'duration.numeric'      =>"Duration không phải là sô",
                        'note.required'          =>"Không tồn tại note",
            ]
        );
        $id = $request->id;
        $data = array(
            'name' => $request->name,
            'start_day' => $request->start_day,
            'duration' => $request->duration,
            'note' => $request->note,
            'class_id' => $request->class_id,
        );
        $checkStartDay= Examination::editExam($id);
        if($errors->fails()|| $data['start_day'] < date('Y-m-d H:i:s') || $checkStartDay['start_day'] <  date('Y-m-d H:i:s') ){
            $arrayErrors = $errors->errors();
            $message = [ "code"=>0,"message"=>$arrayErrors];
            return response()->json($message,200);
        }else{
            Examination::updateExam($data,$id);
            return response()->json(['code'=>1,'message'=>'Chỉnh sửa khóa học thành công!']);
        }
    }
    /**
     * edit kì thi 
     *
     * @param  array  $Request
     * @return edit
     */
    public function editExam(Request $request){
        $id = $request->id;
        $errors = Validator::make($request->all(),
        [
            'id'        => 'required|numeric',
        ],
        [
            'id.required'           =>"Không tồn tại id",
            'id.numeric'           =>"Id không phải là sô",
        ]
        );
        if($errors->fails()){
                $arrayErrors = $errors->errors();
                $message = [ "code"=>0,"message"=>$arrayErrors];
                return response()->json($message,200);
            }else{
                $edit = Examination::editExam($id);
                return response()->json(['code'=>1,'data' => $edit]);
            }
    }
    /**
     * Danh sách tên lớp 
     *
     * @param  array  $Request
     * @return getClass
     */
    public function getNameClass(Request $request){
        $getClass = Examination::getNameClass();
        return response()->json(['code'=>1,'data' => $getClass]);
    }
}
