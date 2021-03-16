<?php
namespace App;

use App\AccessControl\Scopes\CrmOwnerTrait;
use App\Holiday;
use App\Student;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Invoice extends Eloquent
{
    use CrmOwnerTrait;

    protected $table = 'rev_n_exp';

    public static function get_list_invoice($keyword, $record, $start, $sort, $columns)
    {

        $filters = [];

        foreach ($columns as $key => $column) {
            if (!is_null($column['search']['value'])) {
                Log::info('Invoice filter column: ' . var_export($column['search'], true));
                $filters[$column['name']] = $column['search']['value'];
            }
        }

        $totalRecord = Student::select(DB::raw('count(*) as total'))
            ->join('rev_n_exp', 'rev_n_exp.student_id', '=', 'students.id')
            ->leftjoin('classes', 'rev_n_exp.class_id', '=', 'classes.id')
            ->get()->pluck('total')->toArray();

        $totalRecordFilterd = Student::select(DB::raw('count(rev_n_exp.id) as total'))
            ->join('rev_n_exp', 'rev_n_exp.student_id', '=', 'students.id')
            ->leftjoin('classes', 'rev_n_exp.class_id', '=', 'classes.id');

        $search = Student::select('rev_n_exp.*', 'students.name as std_name', 'students.student_code', 'classes.name as c_name')
            ->join('rev_n_exp', 'rev_n_exp.student_id', '=', 'students.id')
            ->leftjoin('classes', 'rev_n_exp.class_id', '=', 'classes.id');

        if ($keyword) {
            $totalRecordFilterd->orwhere('rev_n_exp.invoice_number', 'like', '%' . $keyword . '%')
                ->orwhere('rev_n_exp.payer', 'like', '%' . $keyword . '%')
                ->orwhere('std_name', 'like', '%' . $keyword . '%')
                ->orwhere('students.student_code', 'like', '%' . $keyword . '%')
                ->orwhere('c_name', 'like', '%' . $keyword . '%');

            $search->orwhere('rev_n_exp.invoice_number', 'like', '%' . $keyword . '%')
                ->orwhere('rev_n_exp.payer', 'like', '%' . $keyword . '%')
                ->orwhere('std_name', 'like', '%' . $keyword . '%')
                ->orwhere('students.student_code', 'like', '%' . $keyword . '%')
                ->orwhere('c_name', 'like', '%' . $keyword . '%');
        }

        if (count($filters)) {
            foreach ($filters as $field => $value) {
                Log::info('Invoice filter: ' . var_export($field, true) . ': ' . var_export($value, true));

                if ($field === 'invoice_status' && in_array($value, ['1', '2'])) {
                    $totalRecordFilterd->orwhere(function ($query) {
                        $query->where('invoice_status', 1)
                            ->orwhere('invoice_status', 2);
                    });
                    $search->orwhere(function ($query) {
                        $query->where('invoice_status', 1)
                            ->orwhere('invoice_status', 2);
                    });
                } else {
                    $totalRecordFilterd->orwhere($field, $value);
                    $search->orwhere($field, $value);
                }

            }
        }

        $totalFiltered = $totalRecordFilterd->get()->pluck('total')->toArray();

        if (count($sort) > 0) {
            $search->orderBy($sort['name'], $sort['dir']);
        }

        $data = $search->offset($start)
            ->limit($record)
            ->get();

        $result = [
            "recordsTotal" => $totalRecord[0],
            "recordsFiltered" => $totalFiltered[0],
            "data" => $data,
        ];

        return $result;
    }

    public static function get_last_tutor_duration($student_id, $class_id)
    {
        $result = 0;
        $max_created_at = DB::table('rev_n_exp')
            ->where('student_id', '=', $student_id)
            ->where('class_id', '=', $class_id)
            ->where('type', '=', 1)
            ->max('created_at');

        $query = DB::table('rev_n_exp')
            ->select('*')
            ->where('student_id', '=', $student_id)
            ->where('class_id', '=', $class_id)
            ->where('type', '=', 1)
            ->where('created_at', '=', $max_created_at)
            ->get();

        $start_date = $query[0]->start_date;
        $end_date = $query[0]->end_date;
        $class_id = $query[0]->class_id;
        $old_duration = self::calculate_duration($start_date, $end_date, $class_id);
        $current_duration = self::calculate_duration($start_date, date('Y-m-d'), $class_id);
        if ($current_duration >= $old_duration) {
            return $result;
        } else {
            return $old_duration - $current_duration;
        }
    }

    public static function calculate_duration($start_date, $end_date, $class_id)
    {
        $duration = 0;
        $class = DB::table('classes')->select('schedule')
            ->where('classes.id', '=', $class_id)
            ->first();

        $class_schedule = data_get($class, 'schedule');
        if ($class_schedule == null) {
            return 0;
        }
        $schedule = json_decode($class_schedule, true);
        $schedule_int = [];
        $wdays_int = config('constant.WEEKDAYS.int');
        foreach (array_keys($schedule) as $wday) {
            $schedule_int[] = $wdays_int[$wday];
        }

        $holidays = Holiday::getHolidayInRange($start_date, $end_date);

        $days_of_range = date_diff(date_create($start_date), date_create($end_date))->format('%a');
        $int_start_date = strtotime($start_date);

        for ($i = 1; $i <= (int) $days_of_range + 1; $i++) {
            $day = strtotime('+' . ($i - 1) . ' days', $int_start_date);
            $wday = date('w', $day);
            $full_day = date('Y-m-d', $day);
            if (in_array($wday, $schedule_int) && !in_array($full_day, $holidays)) {
                $duration++;
            }
        }
        return $duration;
    }

    public static function calculate_enddate($start_date, $duration)
    {
        $end_date = $start_date;
        $class = DB::table('classes')->select('schedule')
            ->where('classes.id', '=', $class_id)
            ->first();
        $schedule = explode(',', substr($class->schedule, 0, -1));
        $int_start_date = strtotime($start_date);
        $temp_end_date = date('Y-m-d', strtotime('+' . (int) ($duration * 3) . ' days', $int_start_date));

        $holidays = Holiday::getHolidayInRange($start_date, $temp_end_date);

        for ($i = 1; $i <= (int) $duration + 1; $i++) {
            $day = strtotime('+' . ($i - 1) . ' days', $int_start_date);
            $wday = date('w', $day);
            $full_day = date('Y-m-d', $day);

            if (in_array($full_day, $holidays)) {
                $duration++;
            }

            if (in_array($wday, $schedule)) {
                $end_date = date('Y-m-d', $day);
            }
        }

        return $end_date;
    }

    public function getPaymentHistoryOfStudent($student_id)
    {
        $list = DB::table('rev_n_exp')->select('*')
            ->where('student_id', $student_id)
            ->orderBy("created_by");
        return $list;
    }

    public static function invoice_number_generate($branch_code)
    {
        $prefix = date('Y/m');
        $old_numbers = DB::table('rev_n_exp')
            ->where('invoice_number', 'like', $prefix . '-%-' . $branch_code)
            ->pluck('invoice_number');

        $postfix = $branch_code;

        $max = $prefix . '-000-' . $postfix;

        if ($old_numbers->count()) {
            $max = $old_numbers->max();
        }

        $ary_max = explode('-', $max);
        $number = $ary_max[1] + 1;

        if (strlen($number) < 3) {
            for ($s = strlen($number); $s < 3; $s++) {
                $number = '0' . $number;
            }
        }

        return $prefix . '-' . $number . '-' . $postfix;
    }

    public static function search_invoice($condition = [])
    {
        $query = DB::table('rev_n_exp')
            ->select('rev_n_exp.id',
                'rev_n_exp.created_at',
                'rev_n_exp.invoice_number',
                'students.student_code',
                'students.name',
                'rev_n_exp.payer',
                'rev_n_exp.payment_method',
                'classes.name as class',
                'rev_n_exp.discount',
                'rev_n_exp.discount_type',
                'rev_n_exp.discount_desc',
                'rev_n_exp.invoice_status',
                'rev_n_exp.amount'
            )
            ->join('staffs', 'rev_n_exp.created_by', '=', 'staffs.id')
            ->join('branch', 'branch.id', '=', 'staffs.branch_id')
            ->join('students', 'rev_n_exp.student_id', '=', 'students.id')
            ->join('classes', 'rev_n_exp.class_id', '=', 'classes.id');

        if (!empty($condition['type'])) {
            $query->where('rev_n_exp.type', '=', $condition['type']);
        }

        if (!empty($condition['invoice_status'])) {
            if ($condition['invoice_status'] == 2) {
                $query->whereIn('rev_n_exp.invoice_status', [1, 2]);
            } else {
                $query->where('rev_n_exp.invoice_status', '=', $condition['invoice_status']);
            }
        }

        if (!empty($condition['branch_id'])) {
            $query->where('branch.id', '=', $condition['branch_id']);
        }

        if (!empty($condition['class_id'])) {
            $query->where('rev_n_exp.class_id', '=', $condition['class_id']);
        }

        if (!empty($condition['created_at'])) {
            if (!is_array($condition['created_at'])) {
                $query->where('rev_n_exp.created_at', '=', $condition['created_at']);
            } else {
                $query->whereBetween('rev_n_exp.created_at', $condition['created_at']);
            }
        }

        return $query->get();

    }
}
