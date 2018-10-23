<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TimeTable;
use DB;
use Course;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name', 'status', 'schedule','time','teacher_id','course_id','class_size','created_at','updated_at'];

     /**
     * Lấy danh sách lớp học.
     *
     * @param  integer|  $keyword,$record,$page
     * @return void
     */
    public static function getListClass($keyword,$record,$page = 1)
    {
        $start = ($page - 1) * $record;
        $listClass = Classes::join('courses','courses.id','=','classes.course_id')
                    ->join('teachers','teachers.id','=','classes.teacher_id')
                    ->select('classes.*','teachers.name as teacher_name','courses.name as course_name')
                    ->where('classes.name','like','%'.$keyword.'%')
                    ->orwhere('classes.status','like','%'.$keyword.'%')
                    ->orwhere('classes.schedule','like','%'.$keyword.'%')
                    ->orwhere('classes.time_start','like','%'.$keyword.'%')
                    ->orwhere('classes.start_date','like','%'.$keyword.'%')
                    ->orwhere('classes.class_size','like','%'.$keyword.'%')
                    ->orwhere('teachers.name','like','%'.$keyword.'%')
                    ->orwhere('courses.name','like','%'.$keyword.'%')
                    ->offset($start)->limit($record)
                    ->get();
		                             
		return $listClass;
    }

    /**
	 * Xóa lớp học
	 *
	 * @param  integer|  $idClass
	 * @return void
	 */

    public static function deleteClass($idClass)
    {
    	$recordsRemove = Classes::find($idClass);
    	$recordsRemove->delete();
    }

     /**
     * Tạo mới lớp học
     *
     * @param array| $infoClass
     * @return void
     */
    public static function createClass($infoClass)
    {
    	$newClass = new Classes;
        Classes::insert(
            [
                'class_code'    =>  $infoClass['class_code'],
                'name'          =>  $infoClass['name'],
                'teacher_id'    =>  $infoClass['teacher_id'],
                'schedule'      =>  $infoClass['schedule'],
                'time_start'    =>  $infoClass['time_start'],
                'start_date'    =>  $infoClass['start_date'],
                'duration'      =>  $infoClass['duration'],
                'course_id'     =>  $infoClass['course_id'],
                'class_size'    =>  $infoClass['class_size'],
                'status'        =>  0,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );
    }
    /**
     * Lấy thông tin lớp
     *
     * @param integer| $idClass
     * @return void
     */

    public static function getInfoClass($idClass){
    	$infoClass = Classes::find($idClass);
    	return $infoClass;
    }

    /**
     * Chỉnh sửa lớp học
     *
     * @param array| $infoClass
     * @param integer|$idClass
     * @return void
     */

	public static function editClass($infoClass,$idClass){
    		$editClass = Classes::where('id',$idClass)->update(
                                [
                                    'class_code'    =>  $infoClass['class_code'],
                                    'name'          =>  $infoClass['name'],
                                    'class_size'    =>  $infoClass['class_size'],
                                    'created_at'    =>  date('Y-m-d H:i:s')
                                ]
                            );
	}

    /**
     * Lấy thông tin lớp cần sửa
     *
     * @param integer| $idClass
     * @return void
     */

    public static function getEditClass($idClass){
        $infoClass = Classes::where('id',$idClass)->select("*")->first();
        return $infoClass;
    }

    /**
     * Lấy các học sinh của lớp
     *
     * @param integer| $id
     * @return void
     */

    public static function getStudentOfClass($id)
    {
        $studentOfClass =DB::table('classes')->join('student_classes','classes.id','=','student_classes.class_id')
                    ->join('students','students.id','=','student_classes.student_id')
                    ->select('classes.name as class_name','students.*')
                    ->where('classes.id','=',$id)
                    ->get();

            return $studentOfClass;
    }
    /**
     * Xóa học sinh của lớp
     *
     * @param integer| $idStudent
     * @return void
     */

    public static function deleteStudentOfClass($idStudent)
    {
         StudentClass::where('student_id',$idStudent)->delete();
    }

    /**
     * Tìm học sinh của lớp
     *
     * @param integer| $idStudent
     * @return $idStudentOfClass->count()
     */

    public static function findStudentOfClass($idStudent)
    {
        $idStudentOfClass =  StudentClass::where('student_id',$idStudent)->get();
        return $idStudentOfClass->count();
    }
    /**
     * Tạo thời khóa biểu
     *
     * @param array| $dataTimeTable
     * @param string| $classCode
     * @return $timeTable
     */

    public static function createTimeTable($dataTimeTable,$classCode){
        $idClass=Classes::whereclass_code($classCode)->first();
        $timeTable = DB::table('timetables')->insert(
            [
                'week_days'     =>  $dataTimeTable['week_days'],
                'date'          =>  $dataTimeTable['date'],
                'time'          =>  $dataTimeTable['time'],
                'class_id'      =>  $idClass->id,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );
        return $timeTable;
    }

    /**
     * Lấy thời lượng của khóa học
     *
     * @param string| $classCode
     * @return  $durationCourse->duration
     */

    public static function getDurationOfCourse($classCode){
        $idCourse = Classes::where('class_code',$classCode)->select('course_id')->first();
        $durationCourse = DB::table('courses')->where('id',$idCourse->course_id)->select('duration')
                            ->first();
        return $durationCourse->duration;

    }

    /**
     * Lấy tên giáo viên
     * @return $nameTeacher
     */

    public static function getNameTeacher(){
        $nameTeacher = DB::table('teachers')->select('name','id')->get();
        return $nameTeacher;
    }

    /**
     * Lấy tên của khóa học
     * @return $nameCourse
     */

    public static function getNameCourse(){
        $nameCourse = DB::table('courses')->select('name','id')->get();
        return $nameCourse;
    }

    /**
     * Xóa thời khóa biểu của lớp
     *
     * @param integer| $idClass
     * @return void
     */
    public static function deleteTimeTableOfClass($idClass){
        $timeTable = DB::table('timetables')->where('class_id',$idClass)->delete();
    }
    /**
     * Kiểm tra số học sinh của lớp
     *
     * @param integer| $idClass
     * @return $studentsOfClass
     */

    public static function checkQtyStudentsOfClass($idClass){
        $studentsOfClass = DB::table('student_classes')->where('class_id',$idClass)->count('student_id');
        return $studentsOfClass;
    }

      /**
     * Hiển thị danh sách các lớp đang tuyển sinh.
     *
     * @return $class
     */
    public static function classByStatus(){
        $class = DB::table('classes')
                    ->leftjoin('student_classes','student_classes.class_id','=','classes.id')
                    ->join('courses','courses.id','=','classes.course_id')
                    ->select('classes.*','courses.name as course_name',DB::raw('count(student_classes.student_id) as number_student'))
                    ->where('classes.status',0)
                    ->groupBy('classes.id')->get();
        return $class;
    }

    /**
     * Thêm học sinh vào lớp
     *
     * @param array| $data
     * @return $student
     */

    public static function addStudentToClass1($data){
        $student = DB::table('student_classes')
        ->insert([
            ['student_id' => $data['student_id'],
            'class_id' => $data['class_id'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],]
        ]);
        return $student;
    }

    /**
     * Cập nhật trạng thái của lớp
     *
     * @param array| $data
     * @param array| $id
     * @return $status
     */

    public static function updateClassStatus1($data,$id){
        $status = DB::table('classes')->where('id',$id)
                    ->update([
                        'status' => $data['status'],
                    ]);
        return $status;
    }

}
