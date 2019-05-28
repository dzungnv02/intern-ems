<?php
namespace App\CrmServices;

use DateTime;
use DateTimeZone;
use App\Classes\ZohoCrmConnect;
use App\Student;
use \App\Assessment;
use \App\Branch;
use \App\Classes;
use \App\Parents;
use \App\StudentParent;
use \App\Teacher;
use \App\StudentClass;
use Artisan;

use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Parent_;

class StudentSync {

    protected $zoho_crm;
    protected $crm_module;
    protected $mapping_fields;

    public function __construct()
    {
        $this->zoho_crm = new ZohoCrmConnect();
        $this->crm_module = config('zoho.MODULES.ZOHO_MODULE_STUDENTS');
        $this->mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_STUDENTS');

    }

    public function add_student($record_id)
    {
        Log::info('Adding student: '.$record_id);
        
        $criteria = '(id:equals:' . $record_id . ')';
        $crm_student =  $this->zoho_crm->search($this->crm_module, '', '', $criteria);

        if (count($crm_student) == 0) {
            Log::info('Not found student: '. $record_id); 
            return false;
        }

        $student = $crm_student[0];
        $new_student = new Student;

        $this->save_student($new_student, $student);
    }

    public function edit_student($record_id)
    {
        Log::info('Edit student');

        $criteria = '(id:equals:' . $record_id . ')';
        $crm_student =  $this->zoho_crm->search($this->crm_module, '', '', $criteria);

        if (count($crm_student) == 0) {
            Log::info('Not found student: '. $record_id); 
            return false;
        }

        $student = $crm_student[0];
        $ems_student = Student::getStudentByCrmID($record_id);
        if ($ems_student == null) {
            $ems_student = new Student;
        }

        $this->save_student($ems_student, $student);
    }

    public function delete_student($record_id)
    {
        Log::info('Delete student: '. $record_id);
        $ems_student = Student::getStudentByCrmID($record_id);
        if ($ems_student != null) {
            $ems_student->delete();
        }
    }

    protected function save_student(Student $ems_student, $crm_student)
    {
        $ems_fields = [];
        $crm_fields = [];
        $branch = Branch::getBranchByCrmOwner($crm_student->Owner->id);
        $assessment_list = [];

        foreach ($this->mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        for ($i = 0; $i < count($ems_fields); $i++) {
            $ems_field = $ems_fields[$i];
            $crm_field = $crm_fields[$i];
            if ($crm_field == 'assessment') {
                if (data_get($crm_student, 'assessment_status') == 'Chưa thực hiện' && data_get($crm_student, 'trial_status') == 'Chưa học thử') {
                    $assessment_list['status'] = false;
                } else {
                    $assessment_list['status'] = true;
                }

                foreach ($ems_field as $f_crm => $f_ems) {
                    if ($f_crm == 'assessment_teacher') {
                        $t_crm = data_get($crm_student, $f_crm);
                        $t_ems = is_object($t_crm) ? Teacher::where('crm_id', $t_crm->id)->first() : null;
                        $assessment_list[$f_ems] = data_get($t_ems, 'id');
                    } else if ($f_crm == 'trial_class') {
                        $c_crm = data_get($crm_student, $f_crm);
                        $c_ems = is_object($c_crm) ? Classes::where('crm_id', $c_crm->id)->first() : null;
                        $assessment_list[$f_ems] = data_get($c_ems, 'id');
                    } else if ($f_crm == 'assessment_date' || $f_crm == 'trial_start_date') {
                        $tmp_date = data_get($crm_student, $f_crm) != null ? new DateTime($crm_student->$f_ems) : '';
                        $assessment_list[$f_ems] = $tmp_date != '' ? $this->get_ebay_UTC_8601($tmp_date) : null;
                    } else if ($f_crm != 'assessment_status' && $f_crm != 'trial_status') {
                        $assessment_list[$f_ems] = $crm_student->$f_crm;
                    }
                }
                continue;
            } else if (in_array($crm_field, ['register_branch', 'dependent_staff', 'Contact_Name', 'Owner'])) {
                $value = !is_null(data_get($crm_student, $crm_field)) ? json_encode(data_get($crm_student, $crm_field), JSON_UNESCAPED_UNICODE) : '';
            } else if ($crm_field == 'current_class') {
                $crm_class = data_get($crm_student, $crm_field);
                $cls = $crm_class != null ? Classes::where('crm_id', data_get($crm_class, 'id'))->first() : null;
                $value = $cls != null ? data_get($cls, 'id') : '';
            } else if ($crm_field == 'register_date' || $crm_field == 'branch_transfer_date') {
                if ($crm_student->$crm_field == null) {continue;}
                $tmp_date = new DateTime($crm_student->$crm_field);
                $value = $this->get_ebay_UTC_8601($tmp_date);
            } else if ($crm_field == 'birthyear') {
                if ($crm_student->$crm_field == null || strlen($crm_student->$crm_field) > 4) {
                    $value = '';
                    continue;
                } else if (strlen($crm_student->$crm_field) == 4) {
                    $value = $crm_student->$crm_field;
                }
            } else {
                $value = $crm_student->$crm_field;
            }
            $ems_student->$ems_field = $value;
        }

        $ems_student->register_branch_id = $branch != null ? $branch->id : null;
        $ems_student->save();

        if ($assessment_list['status']) {
            unset($assessment_list['status']);
            $assessment_list['student_id'] = $ems_student->id;
            $assessment_list['staff_id'] = 1;
            Assessment::insertAssessment($assessment_list);
        }

        $student_parent = StudentParent::getParentsOfStudent($ems_student->id);
        if (count($student_parent) == 0) {
            $ems_student_parent = new StudentParent;
            $ems_student_parent->student_id = $ems_student->id;
        }
        else {
            $ems_student_parent = StudentParent::find($student_parent[0]->id);
        }

        $this->mapping_parent($ems_student_parent,$crm_student);
        $this->mapping_classes($crm_student);
    }

    protected function mapping_parent(StudentParent $ems_student_parent, $crm_student)
    {
        $contact_id = $crm_student->Contact_Name->id;
        $criteria = $contact_id != '' ? '(id:equals:' . $contact_id . ')' : '';
        $crm_contacts = $this->zoho_crm->search('Contacts', '', '', $criteria);

        $crm_contact = count($crm_contacts) ? $crm_contacts[0] : null;

        if ($crm_contact == null) {
            return;
        }

        $parent = null;

        if ($crm_contact->Account_Name != null){
            $parent = Parents::getParentByCrmId($crm_contact->Account_Name->id);
            if (!$parent) {
                $parent = new Parents;
            }
        }
        elseif ($crm_student->Contact_Name != null) {
            $parent = Parents::getParentByCrmContactId($crm_student->Contact_Name->id);
            if (!$parent) {
                $parent = new Parents;
            }
        }
        else {
            $parent = new Parents;
        }

        $contacts_fields = config('zoho.MAPPING.ZOHO_MODULE_CONTACTS');
        
        foreach($contacts_fields as $crm_field => $ems_field) {
            $value = $crm_field != 'Owner' ? $crm_contact->$crm_field : json_encode($crm_contact->$crm_field, JSON_UNESCAPED_UNICODE);
            $parent->$ems_field = $value;
        }

        $parent->save();

        $ems_student_parent->parent_id = $parent->id;
        $ems_student_parent->save();
    }

    protected function mapping_classes($crm_student)
    {
        $student_stages = config('zoho.DEAL_STAGES');
        if ($student_stages[$crm_student->Stage] == 0) {
            return;
        }

        $owner = $crm_student->Owner->id;
        $ems_student = Student::getStudentByCrmID($crm_student->id);

        $current_class = data_get($crm_student, 'current_class');
        
        if ($current_class != null) {
            $crm_class_id = data_get($current_class, 'id');
            $ems_class = Classes::getClassByCrmId($crm_class_id);
            if ($ems_class) {
                StudentClass::assignClass(data_get($ems_class, 'id'), data_get($ems_student, 'id'));
                return;
            }
            else {
                $this->sync_crm_class($owner);
                $this->mapping_classes($crm_student);
            }
        }
        else if ($this->sync_crm_class($owner)) {
           $class_list = Classes::getClassByCrmOwner($owner);
           foreach($class_list as $ems_class) {
                $student_list = $this->zoho_crm->getRelatedList('Products', data_get($ems_class, 'crm_id'), 'Deal');
                if (count($student_list) > 0) {
                    foreach($student_list as $std) {
                        if (data_get($std, 'id') == $crm_student->id) {
                            StudentClass::assignClass(data_get($ems_class, 'id'), data_get($ems_student, 'id'));
                            return;
                        }
                    }
                }
           }
        }
    }

    protected function sync_crm_class($owner)
    {
        Artisan::call('zoho:classes', [
            '--getlist' => true,
            '--owner' => $owner
        ]);
        $output = Artisan::output();

        if ( strpos($output, 'end_sync_classes') !== false) {
            return true;
        }

        return false;
    }

    protected function get_ebay_UTC_8601(DateTime $time)
    {
        $t = clone $time;
        $t->setTimezone(new DateTimeZone("Asia/Saigon"));
        return $t->format("Y-m-d H:i:s");
    }
}