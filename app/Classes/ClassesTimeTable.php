<?php
namespace App\Classes;
use Illuminate\Support\Facades\Log;

class ClassesTimeTable
{
    protected $_time_table = [];
    protected $_holidays = [];
    protected $_schedule = [];
    protected $_date_range = ['start_date' => '', 'end_date' => ''];
    protected $_ary_days = [];
    protected $_total_appoiment = 0;
    protected $_week_days = ['sun','mon','tue','wed','thu','fri','sat'];

    public function __construct($params = [])
    {
        if (isset($params['schedule'])) {
            $this->set_schedule($params['schedule']);
        }

        if (isset($params['holidays'])) {
            $this->set_holidays($params['holidays']);
        }

        if (isset($params['date_range'])) {
            $this->set_date_range($params['date_range']);
        }
    }

    public function set_schedule($schedule)
    {
        $this->_schedule = $schedule;
    }

    public function get_schedule()
    {
        return $this->_schedule;
    }

    public function set_holidays($holidays)
    {
        $this->_holidays = $holidays;
    }

    public function set_date_range($date_range)
    {
        if (isset($date_range['start_date']) && isset($date_range['end_date'])) {
            $this->_date_range = $date_range;
        } else {
            $today = date('Y-m-d');
            $this->_date_range = [
                'start_date' => $today,
                'end_date' => date('Y-m-d', strtotime('+1 month')),
            ];
        }
    }

    public function calc_time_table($addition = null)
    {
        $this->_ary_days = $this->dateRange($this->_date_range['start_date'], $this->_date_range['end_date']);
        if ( is_array($this->_ary_days) ) {
            $index = 0;
            foreach ($this->_ary_days as $days) {
                $week_day = $this->_week_days[date('w', strtotime($days))];
                $cur_day = date('Y-m-d', strtotime($days));

                if (isset($this->_schedule[$week_day]) && !in_array($cur_day, $this->_holidays)) {
                    $this->_time_table[$index]['date'] = $cur_day;
                    $this->_time_table[$index]['week_day'] = $week_day;
                    $this->_time_table[$index]['start'] = $this->_schedule[$week_day]['start'];
                    $this->_time_table[$index]['finish'] = $this->_schedule[$week_day]['finish'];
                    if (is_array($addition)) {
                        foreach($addition as $field => $value) {
                            $this->_time_table[$index][$field] = $value;
                        }
                    }
                    $index++;
                }
            }
            
            return $this->_time_table;
        }
        
    }

    public function load_time_table()
    {
    
    }

    public function schedule_of_date()
    {

    }

    public function schedule_of_teacher()
    {

    }

    public function change_schedule()
    {

    }

    protected function dateRange($start_date, $end_date, $step = '+1 day', $format = 'Y/m/d')
    {
        $dates = array();
        $current = strtotime($start_date);
        $last = strtotime($end_date);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
		
        return $dates;
    }
}