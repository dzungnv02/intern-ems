<?php
namespace App\Classes;

class TeacherTimeTable
{
    protected $_start_of_day = '08:00';
    protected $_end_of_day = '20:30';

    protected $_schedules = [];
    protected $_schedule_types = [];
    protected $_time_table = [];
    protected $_teacher_list = ['2', '27'];

    public function __construct()
    {

    }

    public function set_start_of_day(String $time)
    {
        $this->_start_of_day = $time;
    }

    public function set_end_of_day(String $time)
    {
        $this->_end_of_day = $time;
    }

    public function set_schedule_list(Array $list)
    {
        $this->_schedules = $list;
    }

    public function set_teacher_list (Array $list)
    {
        $this->_teacher_list = $list;
    }

    public function render_weekly_time_table()
    {
        $time_table = $this->generate_time_range();

        if (count($this->_schedules) > 0) {
            foreach ($this->_schedules as $key => $schedule) {
                $start_time = date('H:i', strtotime($schedule['start_time']));
                $end_time = date('H:i', strtotime($schedule['end_time']));
                $weekday = date('D', strtotime($schedule['start_time']));
                $teacher = $schedule['teacher_id'];
                $content = $schedule['class_id'];
                $time_table[$start_time][$weekday][$teacher] = $content;
            }
        }
        
        return $time_table;
    }

    protected function generate_time_range()
    {
        $result = [];
        $row_count = 0;
        $start_time = strtotime($this->_start_of_day);
        $end_time = strtotime($this->_end_of_day);

        $halfHourSteps = range($start_time, $end_time, 1800);
        $rows = array_map(function ($time) {
            return date('H:i', $time);
        }, $halfHourSteps);

        $row_count = count($rows);

        $timestamp = strtotime('next Monday');
        $weekdays = [];

        for ($i = 0; $i < 7; $i++) {
            foreach ($this->_teacher_list as $teacher) {
                $weekdays[date('D',$timestamp )][(int)$teacher] = '';
            }
            $timestamp = strtotime('+1 day', $timestamp);
        }

        foreach ($rows as $time) {
            $result[$time] = $weekdays;
        }

        return $result;
    }

}
