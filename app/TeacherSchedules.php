<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherSchedules extends Model
{
    public static function getByTeacher($teacher_id, $class_id = null, $date_range = null)
    {
        $query = DB::table('teacher_schedules')->select('teacher_schedules.*', 'teachers.name')
        ->join('teachers', 'teachers.id', 'teacher_schedules.teacher_id')
        ->where('teacher_id', $teacher_id);
        if ($class_id) {
            $query->where('class_id', $class_id);
        }

        if ($date_range) {
            $query->where('start_time', '>=', $date_range['start'])
                ->where('end_time', '<=', $date_range['end']);
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
}
