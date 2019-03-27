<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Attendance;
use \App\StudentClass;

class AttendanceController extends Controller
{
    public function attendanceTable(Request $request)
    {
        $input = $request->all();
        $class_id = $input['class_id'];
        $data = Attendance::getAttendanceTable($class_id);
        return response()->json(['code' => 1, 'data' => $data], 200);
    }

    public function attendanceCheck(Request $request)
    {
        $input = $request->all();
        $student_id = $input['student_id'];
        $timetable_id = $input['timetable_id'];
        $status = $input['status'];
        $note = isset($input['note']) ? $input['note'] : null;
        $staff = $input['logged_user']->id;
        $result = Attendance::attendanceCheck($timetable_id, $student_id, $status, $note, $staff);
        if ($result) {
            return response()->json(['code' => 1, 'result' => 'success'], 200);
        } else {
            return response()->json(['code' => 0, 'result' => 'fail'], 200);
        }
    }

    public function getAttendanceByDate (Request $request)
    {
        $input = $request->all();
        $timetable_id = $input['timetable_id'];
        $data = Attendance::getAttendanceBySchedule($timetable_id);
        if ($data) {
            return response()->json(['code' => 1, 'data' => $data], 200);
        } else {
            return response()->json(['code' => 0, 'result' => 'fail'], 200);
        }
    }

    public function getAttendanceByStudent (Request $request) 
    {
        try {
            $input = $request->all();
            $student_id = $input['student_id'];
            $attendance = [];
    
            $classes = StudentClass::getClassOfStudent($student_id);
            $data = [];

            if (count($classes)) {
                foreach ($classes as $class) {
                    $data[] = ['id' => $class->id, 'name' => $class->name, 'attendance' => Attendance::getAttendanceByStudent($student_id, $class->id)];
                }
            }

            return response()->json(['code' => 1, 'data' => $data], 200);
        }
        catch(\Exception $e) {
            return response()->json(['code' => 0, 'result' => 'fail', 'message' => $e->getMessage()], 200);
        }
    }
    
}
