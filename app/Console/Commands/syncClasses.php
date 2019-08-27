<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Branch;
use \App\Classes;
use \App\Classes\ZohoCrmConnect;
use \App\Teacher;
use App\Student;
use \App\StudentClass;
use Illuminate\Support\Facades\DB;

class syncClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:classes {--getlist} {--schedule} {--map_student} {--owner=}';
    //php artisan zoho:classes --getlist --owner=2666159000000213025
    //php artisan zoho:classes --schedule --owner=2666159000000213025
    //php artisan zoho:classes --map_student
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get class from ZohoCRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $defaultTimeZone = 'Asia/Saigon';
        if (date_default_timezone_get() != $defaultTimeZone) {
            date_default_timezone_set($defaultTimeZone);
        }

        $fillter_owner_id = $this->option('owner') . '';

        $getlist = $this->option('getlist');
        $map_student = $this->option('map_student');
        if ($getlist) {
            $this->info('start_sync_classes');
            $this->get_list($fillter_owner_id);
            $this->info('end_sync_classes');
        }
        else if ($map_student){
            $this->map_student();
        }
    }

    protected function get_list($fillter_owner_id)
    {
        //$ems_list = Classes::all()->toArray();
        $ems_list = DB::table('classes')->select('*')->get()->toArray();
        $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
        $ems_fields = [];
        $crm_fields = [];
        $insert_list = [];
        $update_list = [];

        $mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_EMS_CLASS');
        foreach ($mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        $this->info('Start sync module: ' . $crm_module);
        $zoho_crm = new ZohoCrmConnect();
        $criteria = $fillter_owner_id != '' ? '(Owner.id:equals:' . $fillter_owner_id . ')' : '';
        $crm_list = $zoho_crm->search($crm_module, '', '', $criteria);
        $zoho_crm->sync($crm_list, $ems_list, $insert_list, $update_list);

        $this->info('Classes count: ' . count($crm_list));

        if (count($insert_list)) {
            foreach ($insert_list as $item) {
                $branch = Branch::getBranchByCrmOwner($item->Owner->id);
                $crm_class = $zoho_crm->getRecordById($crm_module, data_get($item, 'id'));
                $new_class = new Classes;
                for ($i = 0; $i < count($crm_fields); $i++) {
                    $c_field = $crm_fields[$i];
                    $e_field = $ems_fields[$i];

                    if ($c_field == 'L_ch_h_c_trong_tu_n') {
                        $val = data_get($crm_class, $c_field);
                        if ($val != null) {
                            $schedule = json_decode(json_encode($val, JSON_UNESCAPED_UNICODE), true);
                            $new_class->$e_field = $this->classes_schedule_render($schedule[0]);
                            $new_class->crm_schedule = json_encode($val, JSON_UNESCAPED_UNICODE);
                        }
                        continue;
                    } else if ($c_field == 'Product_Active') {
                        $val = data_get($crm_class, $c_field);
                        $start_date =  data_get($crm_class, 'start_date');
                        $now = date('Y-m-d');
                        $status = 0;
                        if ($val  == true && $start_date <= $now) {
                            $status = 2;
                        }
                        else if ($val  == true && $start_date > $now) {
                            $status = 1;
                        }
                        else {
                            $status = 3;
                        }
                        $new_class->status = $status;
                    }
                    else if ($c_field == 'teacher') {
                        $val = data_get($crm_class, $c_field);
                        if ($val != null) {
                            $teacher = Teacher::getTeacherByCrmId(data_get($val, 'id'));  
                            $new_class->teacher_id = $teacher != null ? $teacher->id : null;
                            $new_class->crm_teacher = json_encode($val, JSON_UNESCAPED_UNICODE);
                        }
                        continue;
                    } else {
                        $new_class->$e_field = data_get($crm_class, $c_field);
                    }
                }
                $new_class->branch_id = data_get($branch, 'id');
                $new_class->save();
            }
        }
        $this->info(count($insert_list) . ' record(s) inserted.');

        if (count($update_list)) {
            foreach ($update_list as $item) {
                $class_data = [];
                $branch = Branch::getBranchByCrmOwner($item->Owner->id);

                $crm_class = $zoho_crm->getRecordById($crm_module, data_get($item, 'id'));
                $old_class = Classes::getClassByCrmId(data_get($crm_class, 'id'));
                for ($i = 0; $i < count($crm_fields); $i++) {
                    $c_field = $crm_fields[$i];
                    $e_field = $ems_fields[$i];
                    if ($c_field == 'L_ch_h_c_trong_tu_n') {
                        $val = data_get($crm_class, $c_field);
                        if ($val != null) {
                            $schedule = json_decode(json_encode($val, JSON_UNESCAPED_UNICODE), true);
                            $class_data[$e_field] = $this->classes_schedule_render($schedule[0]);
                            $class_data['crm_schedule'] = json_encode($val, JSON_UNESCAPED_UNICODE);
                        }
                        continue;
                    
                    } else if ($c_field == 'Product_Active') {
                        $val = data_get($crm_class, $c_field);
                        $start_date =  data_get($crm_class, 'start_date');
                        $now = date('Y-m-d');
                        $status = 0;
                        if ($val  == true && $start_date <= $now) {
                            $status = 2;
                        }
                        else if ($val  == true && $start_date > $now) {
                            $status = 1;
                        }
                        else {
                            $status = 3;
                        }
                        $class_data['status'] = $status;
                    }
                    else if ($c_field == 'teacher') {
                        $val = data_get($crm_class, $c_field);
                        if ($val != null) {
                            $teacher = Teacher::where('teachers.crm_id', data_get($val, 'id'))->first();
                            $class_data['teacher_id'] = $teacher != null ? $teacher->id : null;
                            $class_data['crm_teacher'] = json_encode($val, JSON_UNESCAPED_UNICODE);
                            
                        }
                        continue;
                    } else {
                        $value = data_get($crm_class, $c_field);
                        $class_data[$e_field] = $value;
                    }
                    $class_data['branch_id'] = data_get($branch, 'id');
                    $class_data['updated_at'] = date('Y-m-d H:i:s');
                    Classes::updateOne($old_class->id, $class_data);
                    //$old_class->branch_id = data_get($branch, 'id');
                }
            }
        }
        $this->info(count($update_list) . ' record(s) updated.');

    }

    protected function classes_schedule_render($schedule_data)
    {
        $field_list = ['time_1', 'weekday_1', 'time_2', 'weekday_2'];

        $weekdays_vn = [
            'thứ 2' => 'mon',
            'thứ hai' => 'mon',
            'thứ 3' => 'tue',
            'thứ ba' => 'tue',
            'thứ 4' => 'wed',
            'thứ tư' => 'wed',
            'thứ 5' => 'thu',
            'thứ năm' => 'thu',
            'thứ 6' => 'fri',
            'thứ sáu' => 'fri',
            'thứ 7' => 'sat',
            'thứ bảy' => 'sat',
            'cn' => 'sun',
            'chủ nhật' => 'sun',
        ];

        $weekdays_en = [
            'monday' => 'mon',
            'tuesday' => 'tue',
            'wednesday' => 'wed',
            'thursday' => 'thu',
            'friday' => 'fri',
            'saturday' => 'sat',
            'sunday' => 'sun',
        ];

        $weekdays_vn_keys = array_keys($weekdays_vn);
        $weekdays_en_keys = array_keys($weekdays_en);
        $weekdays_en_values = array_values($weekdays_en);

        $schedule_rendered = new \stdClass;
        $tmp = [];
        foreach ($schedule_data as $field => $value) {
            if (in_array($field, $field_list)) {
                $tmp[$field] = strtolower($value);
            }
        }

        $weekday_1 = '';
        $weekday_2 = '';
        $time_1 = ['start' => '', 'finish' => ''];
        $time_2 = ['start' => '', 'finish' => ''];
        if (in_array($tmp['weekday_1'], $weekdays_vn_keys)) {
            $weekday_1 = $weekdays_vn[$tmp['weekday_1']];
        } else if (in_array($tmp['weekday_1'], $weekdays_en_keys)) {
            $weekday_1 = $weekdays_en[$tmp['weekday_1']];
        } else if (in_array($tmp['weekday_1'], $weekdays_en_values)) {
            $weekday_1 = $tmp['weekday_1'];
        }

        if (in_array($tmp['weekday_2'], $weekdays_vn_keys)) {
            $weekday_2 = $weekdays_vn[$tmp['weekday_2']];
        } else if (in_array($tmp['weekday_2'], $weekdays_en_keys)) {
            $weekday_2 = $weekdays_en[$tmp['weekday_2']];
        } else if (in_array($tmp['weekday_2'], $weekdays_en_values)) {
            $weekday_2 = $tmp['weekday_2'];
        }

        $tmp_time = $tmp['time_1'] != '' ? explode('-', $tmp['time_1']) : [];
        if (count($tmp_time) > 0) {
            $time_1['start'] = isset($tmp_time[0]) ? trim($tmp_time[0]) : '';
            $time_1['finish'] = isset($tmp_time[1]) ? trim($tmp_time[1]) : '';
        }

        $tmp_time = $tmp['time_2'] != '' ? explode('-', $tmp['time_2']) : [];
        if (count($tmp_time) > 0) {
            $time_2['start'] = isset($tmp_time[0]) ? trim($tmp_time[0]) : '';
            $time_2['finish'] = isset($tmp_time[1]) ? trim($tmp_time[1]) : '';
        }

        if ($weekday_1) {$schedule_rendered->$weekday_1 = $time_1;}
        if ($weekday_2) {$schedule_rendered->$weekday_2 = $time_2;}

        return json_encode($schedule_rendered);
    }

    protected function map_student () 
    {
        try {
            $ems_list = Classes::all();
            $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
            $zoho_crm = new ZohoCrmConnect();

            foreach ($ems_list as $cls) {
                $this->info($cls->name);
                $crm_students = $zoho_crm->getRelatedList($crm_module, data_get($cls, 'crm_id'), 'Deal');
                $count = $crm_students ? count($crm_students) : 0;

                $this->info('Class '. $cls->name. ' has '. $count . ' student(s)');

                if ($count) {
                    foreach($crm_students as $student) {
                        $ems_student = Student::where('students.crm_id', data_get($student, 'id'))->first();

                        if ($ems_student != null) {
                            StudentClass::assignClass(data_get($cls, 'id'), data_get($ems_student, 'id'));
                        }
                    }
                }
            }
        }
        catch(\Exception $e) {
            $this->info('Has error!');
            $this->info($e->getMessage());
        }
    }

}
