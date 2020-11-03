<?php
namespace App\CrmServices;

use App\Classes\ZohoCrmConnect;
use App\Teacher as EmsTeacher;
use App\Branch;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;

class TeacherSync 
{
    protected $zoho_crm;
    protected $crm_module;
    protected $mapping_fields;

    public function __construct()
    {
        $this->zoho_crm = new ZohoCrmConnect();
        $this->crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_TEACHER');
        $this->mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_EMS_TEACHER');
    }

    public function add_teacher($record_id)
    {
        $ems_teacher = EmsTeacher::getTeacherByCrmId($record_id);
        if ($ems_teacher != null) {
            return;
        }

        $ems_teacher = new EmsTeacher;
        $ems_teacher->crm_id = $record_id;
        $this->save_teacher($ems_teacher);
    }

    public function edit_teacher($record_id)
    {
        $ems_teacher = EmsTeacher::getTeacherByCrmId($record_id);
        if ($ems_teacher == null) {
            $this->add_teacher($ems_teacher);
            return;
        }
        $ary_teacher = json_decode(json_encode($ems_teacher), true);

        $ems_teacher = new EmsTeacher;
        foreach ($ary_teacher as $field => $value) {
            $ems_teacher->$field = $value;
        }

        $this->save_teacher($ems_teacher);
    }

    public function save_teacher($ems_teacher)
    {
        $ems_fields = [];
        $crm_fields = [];
        $teacher_data = [];

        foreach ($this->mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        $crm_teacher = $this->zoho_crm->getRecordById($this->crm_module, data_get($ems_teacher, 'crm_id'));
        if ($crm_teacher == false) {
            return;
        };

        $branch = Branch::getBranchByCrmOwner($crm_teacher->Owner->id);
        $ems_teacher->branch_id = $branch->id;
        $teacher_data['branch_id'] = $branch->id;

        for ($i = 0; $i < count($crm_fields); $i++) {
            $ems_field = $ems_fields[$i];
            $crm_field = $crm_fields[$i];
            $value = $crm_field != 'Owner' ? $crm_teacher->$crm_field : json_encode($crm_teacher->$crm_field, JSON_UNESCAPED_UNICODE);
            //$ems_teacher->$ems_field = $value;
            $teacher_data[$ems_field] = $value;
        }

        if ($ems_teacher !== null) {
            $teacher_data['updated_at'] = date('Y-m-d H:i:s');
            EmsTeacher::updateTeacher($ems_teacher->id, $teacher_data);
        }
        else {
            $teacher_data['created_at'] = date('Y-m-d H:i:s');
            EmsTeacher::insert($teacher_data);
        }

        //$ems_teacher->save();
    }   
}