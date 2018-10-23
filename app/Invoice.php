<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    public static function calculate_tuition_fee ($duration, $class_id) {
        $course = DB::table('courses')->select('courses.*')
        ->join('classes', 'courses.id', '=', 'classes.course_id')
        ->where('classes.id' ,'=', $class_id)
        ->get()->toArray();
        $course_fee = $course[0]->fee;
        return $duration > 0 ? ceil($course_fee/$duration) : 0;
    }

    public static function calculate_duration ($start_date, $end_date, $class_id) {
        $duration = 0;
        $class = DB::table('classes')->select('schedule')
        ->where('classes.id' ,'=', $class_id)
        ->first();
        $schedule = explode(',', substr($class->schedule, 0, -1));
        
        $days_of_range = date_diff(date_create($start_date), date_create($end_date))->format('%a');
        $int_start_date = strtotime($start_date);

        for ($i = 1; $i <= (int)$days_of_range + 1; $i++) {
            $day = strtotime('+'. ($i-1) .' days', $int_start_date);
            $wday = date('w',$day);
            if (in_array($wday, $schedule)) {
                $duration ++;
            }
        }

        return $duration;
    }

    public static function calculate_enddate ($start_date, $duration) {
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
}