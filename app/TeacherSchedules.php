<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Student;

class TeacherSchedules extends Model
{
    public static function getByTeacher($teacher_id, $class_id = null, $date_range = null, $schedule_type = null)
    {
        $query = DB::table('teacher_schedules')->select('teacher_schedules.*', 'teachers.name')
        ->join('teachers', 'teachers.id', 'teacher_schedules.teacher_id');
        
        if (!is_array($teacher_id)) {
            $query->where('teacher_id', $teacher_id);
        }
        else {
            $query->whereIn('teacher_id', $teacher_id);
        }

        if ($class_id != null){
            $query->where('class_id', $class_id);
        }

        if ($date_range) {
            $query->where('start_time', '>=', $date_range['start'])
                ->where('end_time', '<=', $date_range['end']);
        }

        if ($schedule_type) {
            $query->where('appoinment_type',  $schedule_type);
        }

        return $query->get()->toArray();
    }

    public static function insert($data)
    {
        return DB::table('teacher_schedules')->insert($data);
    }

    public static function update_by_teacher($teacher_id, $data)
    {
        return DB::table('teacher_schedules')->where('teacher_id', $teacher_id)->update($data);
    }

    public static function update_by_id($id, $data)
    {
        return DB::table('teacher_schedules')->where('id', $id)->update($data);
    }

    public static function update_assesment_schedule ($student_id, $teacher_id, $assessment_date) 
    {
        DB::table('teacher_schedules')->where('student_id', $student_id)->where('appoinment_type', 2)->delete();
        $student = Student::find($student_id);
        $note = 'Kiểm tra đầu vào cho học sinh '. $student->name;
        $end_time = date('Y-m-d H:i', strtotime($assessment_date . ' +1hour'));
        $data = ['teacher_id' => $teacher_id, 'start_time' =>  $assessment_date, 'end_time' => $end_time, 'student_id' => $student_id, 'desc' => $note, 'appoinment_type' => 2];
        return self::insert($data);
    }

    public static function check_teacher_busy ($teacher_id, $appoinment_time) 
    {
        $appoinment_time = date('Y-m-d H:i', strtotime($appoinment_time));
        $query = DB::table('teacher_schedules')->select('id', 'desc', 'start_time', 'end_time')
                ->where('teacher_id', $teacher_id)
                ->where(function($query) use ($appoinment_time) {
                    $query->where('start_time', '<=', $appoinment_time);
                    $query->where('end_time', '>=', $appoinment_time);
                })
                ->get()->toArray();
        return count($query);
    }
}
