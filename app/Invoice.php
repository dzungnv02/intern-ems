<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\DB;
use App\Branch;
use App\Classes;


class Invoice extends Model
{
    protected $table = 'rev_n_exp';

    public static function get_list_invoice () 
    {
        return  DB::table('rev_n_exp')
            ->select('rev_n_exp.*', 'students.name as std_name', 'students.student_code', 'classes.name as c_name')
            ->join('students', 'rev_n_exp.student_id', '=', 'students.id')
            ->join('classes', 'rev_n_exp.class_id', '=', 'classes.id')
            ->get();
    }
    
    public static function calculate_duration ($start_date, $end_date, $class_id) 
    {
        $duration = 0;
        $class = DB::table('classes')->select('schedule')
        ->where('classes.id' ,'=', $class_id)
        ->first();

        $schedule = json_decode($class->schedule, TRUE);
        $schedule_int = [];
        $wdays_int = config('constant.WEEKDAYS.int');
        foreach (array_keys($schedule) as $wday) {
            $schedule_int[] = $wdays_int[$wday];
        } 
        
        $days_of_range = date_diff(date_create($start_date), date_create($end_date))->format('%a');
        $int_start_date = strtotime($start_date);

        for ($i = 1; $i <= (int)$days_of_range + 1; $i++) {
            $day = strtotime('+'. ($i-1) .' days', $int_start_date);
            $wday = date('w',$day);
            if (in_array($wday, $schedule_int)) {
                $duration ++;
            }
        }

        return $duration;
    }

    public static function calculate_enddate ($start_date, $duration) 
    {
        $end_date = $start_date;
        $class = DB::table('classes')->select('schedule')
        ->where('classes.id' ,'=', $class_id)
        ->first();
        $schedule = explode(',', substr($class->schedule, 0, -1));
        $int_start_date = strtotime($start_date);

        for ($i = 1; $i <= (int)$duration + 1; $i++) {
            $day = strtotime('+'. ($i-1) .' days', $int_start_date);
            $wday = date('w',$day);
            if (in_array($wday, $schedule)) {
                $end_date = date('Y-m-d', $day);
            }
        }

        return $end_date;
    }

    public function getPaymentHistoryOfStudent($student_id)
    {
        $list = DB::table('rev_n_exp')->select('*')->where('student_id', $student_id);
        return $list;
    }

    public static function invoice_number_generate()
    {
        $prefix = date('Y/m');
        $old_numbers = DB::table('rev_n_exp')
                        ->where('invoice_number', 'like', $prefix.'-%')
                        ->pluck('invoice_number');
        $postfix = 'NT';

        $max = $prefix . '-000-' . $postfix;

        if ($old_numbers->count()) {
            $max = $old_numbers->max();
        }

        $ary_max = explode('-', $max);
        $number = $ary_max[1] + 1;

        if (strlen($number) < 3) {
            for($s = strlen($number); $s < 3; $s++) {
                $number = '0'. $number;
            }
        }

        return $prefix . '-'. $number .'-' . $postfix;
    }
}