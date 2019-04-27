<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holiday;
use App\TimeTable;
use App\Classes;
use App\Teacher;
use App\Classes\ClassesTimeTable;
use App\TeacherSchedules;

class TimeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $class_id = $request->class_id;
        $timetable = TimeTable::get_list_time_table($class_id);
        return response()->json(['code' => 1, 'message' => 'ket qua', 'data' => $timetable], 200);
    }

    public function save_time_table (Request $request)
    {
        $input = $request->all();
        $class_id = $input['class_id'];
        $now = date('Y-m-d H:i:s');

        $raw_schedule = $input['time_table'];
        $new_schedules = [];
        $update_schedules = [];

        $old_timetables = TimeTable::get_time_table_by_class($class_id);

        $teacher_schedule_insert = [];
        $teacher_schedule_update = [];

        $class = Classes::find($class_id);

        foreach($raw_schedule as $schedule) {
            $schedule['class_id'] = $class_id;
            $schedule['created_at'] = $now;

            unset ($schedule['teacher_name']);

            $old_teacher_schedule = TeacherSchedules::getByTeacher($schedule['teacher_id'], $class_id);

            if (count($old_teacher_schedule)) 
            {
                foreach($old_teacher_schedule as $teacher_schedule)  {
                    $tsch = TeacherSchedules::find($teacher_schedule->id);
                    $tsch->delete();
                }
            }
            
            array_push($teacher_schedule_insert, [
                'teacher_id' => $schedule['teacher_id'],
                'class_id' => $schedule['class_id'],
                'start_time' => $schedule['date'] . ' ' . $schedule['start'],
                'end_time' => $schedule['date'] . ' ' . $schedule['finish'],
                'desc' => 'Lớp ' . $class->name,
                'appoinment_type' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if (count($old_timetables)) {
                foreach ($old_timetables  as $old) {
                    if (strtotime($old->date) == strtotime($schedule['date']) && !in_array($schedule, $update_schedules)) {
                        unset($schedule['created_at']);
                        $schedule['updated_at'] = $now;
                        array_push($update_schedules, $schedule);
                        break;
                    }
                }

                foreach ($old_timetables  as $old) {
                    if (strtotime($old->date) != strtotime($schedule['date'])  && !in_array($schedule, $update_schedules) &&  !in_array($schedule, $new_schedules)) {
                        array_push($new_schedules, $schedule);
                    }
                }
            }
            else {
                array_push($new_schedules, $schedule);
            }
        }

        if (count($new_schedules)) TimeTable::insert($new_schedules);
        if (count($update_schedules)) {
            foreach ($update_schedules as $schedule) {
                TimeTable::update_by_class($class_id, $schedule['date'], $schedule);
            }
        }

        if (count($teacher_schedule_insert)) TeacherSchedules::insert($teacher_schedule_insert);

        return response()->json(['code' => 1, 'update' => $update_schedules, 'old' => $old_timetables], 200);
    }

    public function calculate_time_table (Request $request)
    {
        $input = $request->all();
        $class_id = $input['class_id'];
        $start_date = $input['start_date'];
        $end_date = $input['end_date'];


        $class = Classes::select('classes.*')
                ->join('branch', 'classes.branch_id', '=', 'branch.id')
                ->where('classes.id', '=', $class_id)
                ->first()
                ->toArray();

        $holidays = Holiday::pluckHolidays();
        $schedule = $class['schedule'] ? json_decode($class['schedule'], true) : [];

        $teacher = Teacher::find($class['teacher_id'])->toArray();

        $params = ['schedule' => $schedule, 'holidays' => $holidays, 'date_range' => ['start_date' => $start_date, 'end_date' => $end_date]];
        $obj_time_table = new ClassesTimeTable($params);
        $time_table = $obj_time_table->calc_time_table(['teacher_id' => $class['teacher_id'], 'teacher_name' => $teacher['name']]);
        return response()->json(['code' => 1, 'data' => $time_table ], 200);
    }

}
