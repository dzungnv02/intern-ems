<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class PointExam extends Model
{

    public $timestamps = false;
    public $table = 'point_exams';
    /**
     * Danh sách học viên theo kì thi.
     *
     * @param  string  $examination_id
     * @return getList
     */
    public static function getList($examination_id){
        $getList =DB::table('exams')->join('classes','classes.id','=','exams.class_id')
        ->join('student_classes','classes.id','=','student_classes.class_id')
        ->join('students','students.id','=','student_classes.student_id')
        ->select('classes.name as class_name','students.*','exams.id as exams_id')
        ->where('exams.id','=',$examination_id)
        ->get();

            return $getList;
    }
    /**
     * Thêm điểm học viên.
     *
     * @param  array  $addPoint
     * @return void
     */
    public static function addPointStudent($addPoint){
        $point = PointExam::insert(['point'=> $addPoint['point'],
                                        'examination_id'=> $addPoint['exams_id'],
                                        'created_at'=>  date('Y-m-d H:i:s'),
                                        'updated_at' =>  date('Y-m-d H:i:s'),
                                        'student_id'=>$addPoint['student_id'],
                                        ]);
        return $point;
    }
    /**
     * Danh sách điểm 
     *
     * @param  string  $class_id
     * @return getList
     */
    public static function getListPoint($class_id){
        $getList = PointExam::join('exams','point_exams.examination_id','=','exams.id')
                    ->join('students','students.id','=','point_exams.student_id')
                    ->join('classes','classes.id','=','exams.class_id')
                    ->select('point_exams.point','exams.name as exam_name','students.name as student_name','students.id as student_id','classes.name as class_name')
                    ->where('classes.id','=',$class_id)
                    ->get();
        return $getList;
    }
    /**
     * Tìm kì thi
     *
     * @param  string  $exam_id
     * @return searchExam
     */
    public static function searchExam($exam_id){
        $searchExam= PointExam::where('examination_id','=',$exam_id)->get();
        return $searchExam;
    }
    /**
     * Xóa các điểm theo kì thi
     *
     * @param  string  $exam_id
     * @return void
     */
    public static function deleteExam($exam_id){
        $deleteExam = PointExam::where('examination_id','=',$exam_id)->delete();
        return $deleteExam;
    }
    /**
     * Danh sách điểm 
     *
     * @param  string  $exam_id
     * @return getPointExam
     */
    public static function getPointExam($exam_id){
        $getPointExam = PointExam::join('students','students.id','=','point_exams.student_id')
                        ->select('students.name as student_name','students.student_code','point_exams.point','students.id as student_id')
                        ->where('point_exams.examination_id','=',$exam_id)
                        ->get();
                        return $getPointExam;
    }
    /**
     * update kì thi.
     *
     * @param  array  $data,$id
     * @return void
     */
    public static function updatePoint($data){
        $updateExam = PointExam::where('student_id',$data->student_id)->where('examination_id',$data->examination_id)
        ->update([
            'point' => $data['point'],
            'updated_at' =>  date('Y-m-d H:i:s')
        ]);
        return $updateExam;
    }
}
