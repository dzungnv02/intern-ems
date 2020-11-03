<?php
namespace App\CrmServices;

use App\Branch;
use App\Classes as EmsClass;
use App\Classes\ZohoCrmConnect;
use App\CrmServices\StudentSync;
use App\CrmServices\TeacherSync;
use App\Student as EmsStudent;
use App\StudentClass;
use App\Teacher;

class ClassesSync
{
    protected $zoho_crm;
    protected $crm_module;
    protected $mapping_fields;

    public function __construct()
    {
        $this->zoho_crm = new ZohoCrmConnect();
        $this->crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
        $this->mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_EMS_CLASS');
    }

    public function add_class($record_id)
    {
        $ems_class = EmsClass::getClassByCrmId($record_id);
        if ($ems_class != null) {
            return;
        }

        $ems_class = new \stdClass;
        $ems_class->crm_id = $record_id;
        $this->save_class($ems_class);

    }

    public function edit_class($record_id)
    {
        $ems_class = EmsClass::getClassByCrmId($record_id);
        if ($ems_class == null) {
            //dump('ADD for not exist!');
            $this->add_class($record_id);
            return;
        }

        $this->save_class($ems_class);

    }

    public function delete_class($record_id)
    {

    }

    protected function save_class($ems_class)
    {
        $ems_fields = [];
        $crm_fields = [];

        $class_data = [];

        foreach ($this->mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        $crm_class = $this->zoho_crm->getRecordById($this->crm_module, data_get($ems_class, 'crm_id'));
        if ($crm_class == false || $crm_class->Owner->id == '2666159000000122009') {
            return;
        };

        $branch = Branch::getBranchByCrmOwner($crm_class->Owner->id);
        $ems_class->branch_id = $branch->id;

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
            } 
            else if ($c_field == 'Product_Active') {
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
                    $teacher = Teacher::getTeacherByCrmId(data_get($val, 'id'));
                    if ($teacher == null) {
                        $teacherSync = new TeacherSync();
                        $teacher = new Teacher;
                        $teacher->crm_id = data_get($val, 'id');
                        $teacherSync->save_teacher($teacher);
                        $teacher = Teacher::getTeacherByCrmId(data_get($val, 'id'));
                    }
                    $class_data['teacher_id'] = $teacher != null ? $teacher->id : null;
                    $class_data['crm_teacher'] = json_encode($val, JSON_UNESCAPED_UNICODE);
                }
                continue;
            } else {
                $class_data[$e_field] = data_get($crm_class, $c_field);
            }
        }
        if (isset($ems_class->id)) {
            EmsClass::updateOne($ems_class->id, $class_data);
        } else {
            $ems_id = EmsClass::insertOne($class_data);
            $ems_class->id = $ems_id;
        }
        
        $this->get_student_list($ems_class);
    }

    protected function get_student_list($ems_class)
    {
        $list = $this->zoho_crm->getRelatedList($this->crm_module, data_get($ems_class, 'crm_id'), 'Deal');
        if (!$list) {
            return;
        }

        $studentSync = new StudentSync();

        foreach ($list as $crm_student) {
            $crm_id = data_get($crm_student, 'id');
            $ems_student = EmsStudent::getStudentByCrmID($crm_id);
            if ($ems_student == null) {
                $ems_student = new EmsStudent;
                $studentSync->add_student($crm_id, false);
                $ems_student = EmsStudent::getStudentByCrmID($crm_id);
            }
            StudentClass::assignClass(data_get($ems_class, 'id'), data_get($ems_student, 'id'));
        }

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
}
