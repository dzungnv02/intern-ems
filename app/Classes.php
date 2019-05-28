<?php

namespace App;

use Course;
use DB;
use Illuminate\Database\Eloquent\Model;
use TimeTable;
use App\AccessControl\Scopes\CrmOwnerTrait;

class Classes extends Model
{
    use CrmOwnerTrait;
    protected $table = 'classes';
    protected $fillable = ['name', 'branch_id', 'status', 'schedule', 'time', 'teacher_id', 'course_id', 'course_name', 'price', 'max_seat', 'created_at', 'updated_at'];

    /**
     * Lấy danh sách lớp học.
     *
     * @param  integer|  $keyword,$record,$page
     * @return void
     */
    public static function getListClass($keyword = null, $record = 0, $page = 1)
    {
        $start = ($page - 1) * $record;
        $listClass = Classes::leftJoin('teachers', 'teachers.id', '=', 'classes.teacher_id')
            ->leftJoin('branch','branch.id', '=', 'classes.branch_id' )
            ->leftJoin('student_classes', 'student_classes.class_id', '=', 'classes.id')
            ->select('classes.*', 'teachers.name as teacher_name', 'branch.branch_name', DB::raw('count(student_id) as seat_count'))
            ->groupBy('classes.id');
        if ($keyword != null) {
            $listClass->where('classes.name', 'like', '%' . $keyword . '%')
                ->orwhere('classes.status', 'like', '%' . $keyword . '%')
                ->orwhere('classes.schedule', 'like', '%' . $keyword . '%')
                ->orwhere('classes.start_date', 'like', '%' . $keyword . '%')
                ->orwhere('classes.max_seat', 'like', '%' . $keyword . '%')
                ->orwhere('teachers.name', 'like', '%' . $keyword . '%');
        }

        return $listClass->get();
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
                'class_code' => $infoClass['class_code'],
                'name' => $infoClass['name'],
                'teacher_id' => $infoClass['teacher_id'],
                'schedule' => $infoClass['schedule'],
                'start_date' => $infoClass['start_date'],
                'duration' => $infoClass['duration'],
                'course_id' => $infoClass['course_id'],
                'class_size' => $infoClass['class_size'],
                'status' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public static function insertOne($record)
    {
        return DB::table('classes')->insertGetId($record);
    }

    public static function updateOne($id, $data)
    {
        return DB::table('classes')->where('id', $id)->update($data);
    }

    /**
     * Lấy thông tin lớp
     *
     * @param integer| $idClass
     * @return void
     */

    public static function getInfoClass($idClass)
    {
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

    public static function editClass($infoClass, $idClass)
    {
        $editClass = Classes::where('id', $idClass)->update(
            [
                'class_code' => $infoClass['class_code'],
                'name' => $infoClass['name'],
                'class_size' => $infoClass['class_size'],
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Lấy thông tin lớp cần sửa
     *
     * @param integer| $idClass
     * @return void
     */

    public static function getEditClass($idClass)
    {
        $infoClass = Classes::where('classes.id', $idClass)->select("classes.*")
                    ->join('branch', 'classes.branch_id', '=', 'branch.id')
                    ->first();
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
        $studentOfClass = DB::table('classes')->join('student_classes', 'classes.id', '=', 'student_classes.class_id')
            ->join('students', 'students.id', '=', 'student_classes.student_id')
            ->select('classes.name as class_name', 'students.*')
            ->where('classes.id', '=', $id)
            ->get();

        return $studentOfClass;
    }
    /**
     * Xóa học sinh của lớp
     *
     * @param integer| $idStudent
     * @return void
     */

    public static function deleteStudentOfClass($class_id, $student_id)
    {
        StudentClass::where('student_id', $student_id)->where('class_id', $class_id)->delete();
    }

    /**
     * Tìm học sinh của lớp
     *
     * @param integer| $idStudent
     * @return $idStudentOfClass->count()
     */

    public static function findStudentOfClass($class_id, $student_id)
    {
        $result = StudentClass::where('student_id', $student_id)
                    ->where('class_id', $class_id)->get();
        return $result->count();
    }
    /**
     * Tạo thời khóa biểu
     *
     * @param array| $dataTimeTable
     * @param string| $classCode
     * @return $timeTable
     */

    public static function createTimeTable($dataTimeTable, $classCode)
    {
        $idClass = Classes::whereclass_code($classCode)
                    ->join('branch', 'classes.branch_id', '=', 'branch.id')
                    ->first();
        $timeTable = DB::table('timetables')->insert(
            [
                'week_days' => $dataTimeTable['week_days'],
                'date' => $dataTimeTable['date'],
                'time' => $dataTimeTable['time'],
                'class_id' => $idClass->id,
                'created_at' => date('Y-m-d H:i:s'),
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

    public static function getDurationOfCourse($classCode)
    {
        $idCourse = Classes::where('class_code', $classCode)->select('course_id')->first();
        $durationCourse = DB::table('courses')->where('id', $idCourse->course_id)->select('duration')
            ->first();
        return $durationCourse->duration;

    }

    /**
     * Lấy tên giáo viên
     * @return $nameTeacher
     */

    public static function getNameTeacher()
    {
        $nameTeacher = DB::table('teachers')->select('name', 'id')->get();
        return $nameTeacher;
    }

    /**
     * Lấy tên của khóa học
     * @return $nameCourse
     */

    public static function getNameCourse()
    {
        $nameCourse = DB::table('courses')->select('name', 'id')->get();
        return $nameCourse;
    }

    /**
     * Xóa thời khóa biểu của lớp
     *
     * @param integer| $idClass
     * @return void
     */
    public static function deleteTimeTableOfClass($idClass)
    {
        $timeTable = DB::table('timetables')->where('class_id', $idClass)->delete();
    }
    /**
     * Kiểm tra số học sinh của lớp
     *
     * @param integer| $idClass
     * @return $studentsOfClass
     */

    public static function checkQtyStudentsOfClass($idClass)
    {
        $studentsOfClass = DB::table('student_classes')->where('class_id', $idClass)->count('student_id');
        return $studentsOfClass;
    }

    /**
     * Hiển thị danh sách các lớp đang tuyển sinh.
     *
     * @return $class
     */
    public static function classByStatus()
    {
        $class = DB::table('classes')
            ->leftjoin('student_classes', 'student_classes.class_id', '=', 'classes.id')
            ->select('classes.*', DB::raw('count(student_classes.student_id) as number_student'))
            ->where('classes.status', 0)
            ->groupBy('classes.id')->get();
        return $class;
    }

    /**
     * Thêm học sinh vào lớp
     *
     * @param array| $data
     * @return $student
     */

    public static function addStudentToClass1($data)
    {
        $result = DB::table('student_classes')->insert($data);
        return $result;
    }

    /**
     * Cập nhật trạng thái của lớp
     *
     * @param array| $data
     * @param array| $id
     * @return $status
     */

    public static function updateClassStatus1($data, $id)
    {
        $status = DB::table('classes')->where('id', $id)
            ->update([
                'status' => $data['status'],
            ]);
        return $status;
    }

    public static function getClassByCrmId($crm_id)
    {
        return Classes::select('*')->where('crm_id', '=', $crm_id)->first();
    }

    public static function getClassByCrmOwner($crm_owner)
    {
        $result = DB::table('classes')->select('classes.*')
            ->join('branch', 'branch.id', 'classes.branch_id')
            ->where('branch.crm_owner', '=', $crm_owner)->get();
        return $result;
    }

}
