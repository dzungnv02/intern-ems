<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    protected $table = 'attendance';

    public static function getAttendanceTable ($class_id) {
        $data = [
            'present_status' => 1,
            'absent_status' => -1,
            'late_status' => -2,
            'class_id_1' => $class_id,
            'class_id_2' => $class_id,
            'class_id_3' => $class_id,
            'class_id_4' => $class_id
        ];

        $sql = 'SELECT 
                        students.id, 
                        students.name,
                        count(IF( (attendance.status= :present_status AND classes.id = :class_id_1) ,1,NULL)) as present, 
                        count(IF( (attendance.status= :absent_status AND classes.id = :class_id_2) ,1,NULL)) as absent,
                        count(IF( (attendance.status= :late_status AND classes.id = :class_id_3) ,1,NULL)) as late
                FROM 
                    students INNER JOIN student_classes ON students.id = student_classes.student_id
                    INNER JOIN classes ON classes.id = student_classes.class_id
                    LEFT JOIN attendance ON attendance.student_id = students.id
                WHERE 
                    classes.id = :class_id_4
                GROUP BY students.id,students.name, attendance.status
                ORDER BY students.id';

        return DB::select($sql, $data);
    }

    public static function attendanceCheck($timetable_id, $student_id, $status, $note, $staff_id)
    {
        $exists = DB::table('attendance')
                ->where('timetable_id' , '=', $timetable_id)
                ->where('student_id', '=', $student_id)
                ->count();
        
        if ($exists) {
            return DB::table('attendance') 
                    ->where('timetable_id' , '=', $timetable_id)
                    ->where('student_id', '=', $student_id)
                    ->update(['status' => $status, 
                    'note' => $note, 
                    'staff_id' => $staff_id,
                    'updated_at' => date('Y-m-d H:i:s')]);
        }
        else {
            return DB::table('attendance')->insert(['timetable_id' => $timetable_id, 
            'student_id' => $student_id, 
            'status' => $status, 
            'note' => $note,
            'staff_id' => $staff_id,
            'created_at' => date('Y-m-d H:i:s')]);
        }
    }

    public static function getAttendanceBySchedule ($timetable_id)
    {
        $attendances = DB::table('attendance')
                ->where('timetable_id' , '=', $timetable_id)
                ->select('attendance.*')
                ->get();

        return $attendances;
    }
}
