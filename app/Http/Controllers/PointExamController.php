<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\PointExam;


class PointExamController extends Controller
{
    /**
     * Danh sách học sinh theo các kì thi
     * @param  array  $Request
     * @return data
     */
    public function getListStudent(Request $request){
         $examid = $request->examid;
        $errors = Validator::make($request->all(),
        [
                    'examid' => 'required|numeric'
        ],
        [
                    'examid.required'     =>"Không tồn tại id kì thi",
                    'examid.numeric'      =>"Id kì thi phải là sô",
                   
        ]
    );
    if($errors->fails()){
        $arrayErrors = $errors->errors();
        $message = [ "code"=>0,"message"=>$arrayErrors];
        return response()->json($message,200);
    }else{
        $data= PointExam::getList($examid);
        return response()->json(['code'=>1,'data'=>$data]);
    }
       
    }


    /**
     * Thêm điểm cho học sinh theo các kì thi
     * @param  array  $Request
     * @return void
     */
    public function addPointStudent(Request $request){
        $data =array();
        $exams_id = $request->exams_id[0];
        $searchExam = count(PointExam::searchExam($exams_id));
        if($searchExam > 0 ){
            $deleteExam =PointExam::deleteExam($exams_id);
        }
       for($i=0;$i<count($request->point);$i++){
            $data['point'] = $request->point[$i];
            $data['student_id'] = $request->student_id[$i];
            $data['exams_id'] =  $exams_id;
            PointExam::addPointStudent($data);
       }
        return response()->json(['code'=>1,'message'=>'Thêm điểm thành công']);
    }

    /**
     * Lấy danh sách điểm các kì thi của theo lơp
     * @param  array  $Request
     * @return data
     */
    public function getListPointExam(Request $request){
        $class_id = $request->class_id;
        $errors = Validator::make($request->all(),
        [
            'class_id'           => 'required|numeric',
        ],
        [
            'class_id.required'  =>"Không tồn tại id",
            'class_id.numeric'  =>"id phải là số",

        ]
    );
        if($errors->fails()){
            $arrayErrors = $errors->errors();
            $message = [ "code"=>0,"message"=>$arrayErrors];
            return response()->json($message,200);
        }else{
            $data = PointExam::getListPoint($class_id);
            return response()->json(['code'=>1,'data' => $data]);
        }
    }
    /**
     * Lấy danh sách điểm của lớp theo kì thi
     * @param  array  $Request
     * @return getPointExam
     */
        public function getPointExam(Request $request){
            $examid= $request->examid;
            $errors = Validator::make($request->all(),
            [
                'examid'           => 'required|numeric',
            ],
            [
                'examid.required'  =>"Không tồn tại id",
                'examid.numeric'  =>"id phải là số",
    
            ]
        );
            if($errors->fails()){
                $arrayErrors = $errors->errors();
                $message = [ "code"=>0,"message"=>$arrayErrors];
                return response()->json($message,200);
            }else{
                $getPointExam =PointExam::getPointExam($examid);
                return response()->json(['code'=>1,'data' => $getPointExam]);
            }
           
    }
    public function  updatePoint(Request $request){
        $errors = Validator::make($request->all(),
            [
                        'student_id'        => 'required|numeric',
                        'examination_id'      =>'required|numeric',
                        'point'  =>'required|numeric',
            ],
            [
                        'student_id.required'           =>"Không tồn tại student_id",
                        'examination_id.required'         =>"Không tồn tại examination_id",
                        'point.required'   =>"Không tồn tại point",
                        'examination_id.numeric'      =>"Duration không phải là sô",
                        'student_id.numeric'      =>"Duration không phải là sô",
                        'point.numeric'      =>"Duration không phải là sô",

            ]
        );
        // $data = array(
        //     'student_id' => $request->student_id,
        //     'examination_id' => $request->examination_id,
        //     'point' => $request->point,
        // );
        if($errors->fails()){
            $arrayErrors = $errors->errors();
            $message = [ "code"=>0,"message"=>$arrayErrors];
            return response()->json($message,200);
        }else{
            PointExam::updatePoint($request);
            return response()->json(['code'=>1,'message'=>'Chỉnh sửa khóa học thành công!']);
        }
    }


}
