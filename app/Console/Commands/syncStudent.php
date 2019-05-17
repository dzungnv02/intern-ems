<?php

namespace App\Console\Commands;

use DateTime;
use DateTimeZone;
use Illuminate\Console\Command;
use \App\Assessment;
use \App\Branch;
use \App\Classes;
use \App\Classes\ZohoCrmConnect;
use \App\Parents;
use \App\Student;
use \App\StudentParent;
use \App\Teacher;

class syncStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:student {--getlist} {--map_parent} {--owner=}';
    //php artisan zoho:student --getlist --owner=2666159000000213025

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get student from ZohoCRM';

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
        $get_list = $this->option('getlist');
        $parent = $this->option('map_parent');

        if ($get_list) {
            $this->get_list($fillter_owner_id);
        }

        if ($parent) {
            $this->map_parents($fillter_owner_id);
        }
    }

    protected function get_list($fillter_owner_id)
    {
        $ems_list = Student::all()->toArray();

        $fillter_field = $fillter_owner_id != '' ? 'Owner.id' : '';
        $crm_module = config('zoho.MODULES.ZOHO_MODULE_STUDENTS');
        $ems_fields = [];
        $crm_fields = [];
        $insert_list = [];
        $update_list = [];

        $mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_STUDENTS');
        foreach ($mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        $this->info('Start sync module: ' . $crm_module);

        $zoho_crm = new ZohoCrmConnect();
        $criteria = $fillter_owner_id != '' ? '(Owner.id:equals:' . $fillter_owner_id . ')' : '';

        $crm_list = $zoho_crm->search($crm_module, '', '', $criteria);

        $zoho_crm->sync($crm_list, $ems_list, $insert_list, $update_list, $fillter_field, $fillter_owner_id);

        if (count($insert_list) > 0) {
            $assessment_list = [];
            foreach ($insert_list as $student) {
                $new_student = new Student;
                $branch = $student->register_branch != null ? Branch::getBranchByCrmOwner($student->register_branch->id) : null;
                for ($i = 0; $i < count($ems_fields); $i++) {
                    $ems_field = $ems_fields[$i];
                    $crm_field = $crm_fields[$i];
                    if ($crm_field == 'assessment') {
                        if (data_get($student, 'assessment_status') == 'Chưa thực hiện' && data_get($student, 'trial_status') == 'Chưa học thử') {
                            $assessment_list['status'] = false;
                        } else {
                            $assessment_list['status'] = true;
                        }

                        foreach ($ems_field as $f_crm => $f_ems) {
                            if ($f_crm == 'assessment_teacher') {
                                $t_crm = data_get($student, $f_crm);
                                $t_ems = is_object($t_crm) ? Teacher::where('crm_id', $t_crm->id)->first() : null;
                                $assessment_list[$f_ems] = data_get($t_ems, 'id');
                            } else if ($f_crm == 'trial_class') {
                                $c_crm = data_get($student, $f_crm);
                                $c_ems = is_object($c_crm) ? Classes::where('crm_id', $c_crm->id)->first() : null;
                                $assessment_list[$f_ems] = data_get($c_ems, 'id');
                            } else if ($f_crm == 'assessment_date' || $f_crm == 'trial_start_date') {
                                $tmp_date = data_get($student, $f_crm) != null ? new DateTime($student->$f_ems) : '';
                                $assessment_list[$f_ems] = $tmp_date != '' ? $this->get_ebay_UTC_8601($tmp_date) : null;
                            } else if ($f_crm != 'assessment_status' && $f_crm != 'trial_status') {
                                $assessment_list[$f_ems] = $student->$f_crm;
                            }
                        }
                        continue;
                    } else if (in_array($crm_field, ['register_branch', 'dependent_staff', 'Contact_Name', 'Owner'])) {
                        $value = !is_null(data_get($student, $crm_field)) ? json_encode(data_get($student, $crm_field), JSON_UNESCAPED_UNICODE) : '';
                    } else if ($crm_field == 'current_class') {
                        $crm_class = data_get($student, $crm_field);
                        $cls = $crm_class != null ? Classes::where('crm_id', data_get($crm_class, 'id'))->first() : null;
                        $value = $cls != null ? data_get($cls, 'id') : '';
                    } else if ($crm_field == 'register_date' || $crm_field == 'branch_transfer_date') {
                        if ($student->$crm_field == null) {continue;}
                        $tmp_date = new DateTime($student->$crm_field);
                        $value = $this->get_ebay_UTC_8601($tmp_date);
                    } else if ($crm_field == 'birthyear') {
                        if ($student->$crm_field == null || strlen($student->$crm_field) > 4) {
                            $value = '';
                            continue;
                        } else if (strlen($student->$crm_field) == 4) {
                            $value = $student->$crm_field;
                        }
                    } else {
                        $value = $student->$crm_field;
                    }
                    $new_student->$ems_field = $value;
                }

                $new_student->register_branch_id = $branch != null ? $branch->id : null;
                $new_student->save();

                if (!$assessment_list['status']) {
                    continue;
                } else {
                    unset($assessment_list['status']);
                    $assessment_list['student_id'] = $new_student->id;
                    $assessment_list['staff_id'] = 1;
                    Assessment::insertAssessment($assessment_list);
                }
            }
        }

        $this->info(count($insert_list) . ' record(s) inserted.');

        if (count($update_list)) {
            $data = [];
            $assessment_list = [];
            foreach ($update_list as $student) {
                $old_student = Student::where('crm_id', $student->id)->first();
                $old_assessment = Assessment::where('student_id', $old_student->id)->first();
                $ass_id = data_get($old_assessment, 'id');

                $branch = $student->register_branch != null ? Branch::getBranchByCrmOwner($student->register_branch->id) : null;

                for ($i = 0; $i < count($ems_fields); $i++) {
                    $ems_field = $ems_fields[$i];
                    $crm_field = $crm_fields[$i];
                    if ($crm_field == 'assessment') {
                        if (data_get($student, 'assessment_status') == 'Chưa thực hiện' && data_get($student, 'trial_status') == 'Chưa học thử') {
                            $assessment_list['status'] = false;
                        } else {
                            $assessment_list['status'] = true;
                        }

                        foreach ($ems_field as $f_crm => $f_ems) {
                            if ($f_crm == 'assessment_teacher') {
                                $t_crm = data_get($student, $f_crm);
                                $t_ems = is_object($t_crm) ? Teacher::where('crm_id', $t_crm->id)->first() : null;
                                $assessment_list[$f_ems] = data_get($t_ems, 'id');
                            } else if ($f_crm == 'trial_class') {
                                $c_crm = data_get($student, $f_crm);
                                $c_ems = is_object($c_crm) ? Classes::where('crm_id', $c_crm->id)->first() : null;
                                $assessment_list[$f_ems] = data_get($c_ems, 'id');
                            } else if ($f_crm == 'assessment_date' || $f_crm == 'trial_start_date') {
                                $tmp_date = data_get($student, $f_crm) != null ? new DateTime($student->$f_ems) : '';
                                $assessment_list[$f_ems] = $tmp_date != '' ? $this->get_ebay_UTC_8601($tmp_date) : null;
                            } else if ($f_crm != 'assessment_status' && $f_crm != 'trial_status') {
                                $assessment_list[$f_ems] = $student->$f_crm;
                            }
                        }

                        continue;
                    } else if (in_array($crm_field, ['register_branch', 'dependent_staff', 'Contact_Name', 'Owner'])) {
                        $value = !is_null(data_get($student, $crm_field)) ? json_encode(data_get($student, $crm_field), JSON_UNESCAPED_UNICODE) : '';
                    } else if ($crm_field == 'current_class') {
                        $crm_class = data_get($student, $crm_field);
                        $cls = $crm_class != null ? Classes::where('crm_id', data_get($crm_class, 'id'))->first() : null;
                        $value = $cls != null ? data_get($cls, 'id') : '';
                    } else if ($crm_field == 'register_date' || $crm_field == 'branch_transfer_date') {
                        if ($student->$crm_field == null) {
                            continue;
                        }
                        $tmp_date = new DateTime($student->$crm_field);
                        $value = $this->get_ebay_UTC_8601($tmp_date);
                    } else if ($crm_field == 'birthyear') {
                        if ($student->$crm_field == null || strlen($student->$crm_field) > 4) {
                            $value = '';
                            continue;
                        } else if (strlen($student->$crm_field) == 4) {
                            $value = $student->$crm_field;
                        }
                    } else {
                        $value = $student->$crm_field;
                    }
                    $old_student->$ems_field = $value;
                }

                $old_student->register_branch_id = $branch != null ? $branch->id : null;
                $old_student->save();

                if (!$assessment_list['status']) {
                    continue;
                } else {
                    unset($assessment_list['status']);
                    $assessment_list['student_id'] = $old_student->id;
                    $assessment_list['staff_id'] = 1;
                    if ($ass_id) {
                        Assessment::updateAssessment($ass_id, $assessment_list);
                    } else {
                        Assessment::insertAssessment($assessment_list);
                    }
                }
            }
        }

        $this->info(count($update_list) . ' record(s) updated.');
    }

    protected function map_parents($fillter_owner_id)
    {
        $zoho_crm = new ZohoCrmConnect();
        $criteria = $fillter_owner_id != '' ? '(Owner.id:equals:' . $fillter_owner_id . ')' : '';
        $crm_module = 'Contacts';

        $crm_contacts = $zoho_crm->search('Contacts', '', '', $criteria);
        $crm_accounts = $zoho_crm->search('Accounts', '', '', $criteria);
        $crm_deals = $zoho_crm->search('Deals', '', '', $criteria);
        $count = 0;
        if (count($crm_deals)) {
            foreach ($crm_deals as $student) {
                $deal_contact_name = data_get($student, 'Contact_Name');
                if ($deal_contact_name != null) {
                    foreach ($crm_contacts as $contact) {
                        $contact_id = data_get($contact, 'id');
                        if ($contact_id == $deal_contact_name->id) {
                            $account = data_get($contact, 'Account_Name');
                            if ($account != null) {
                                $parent = Parents::where('crm_id', $account->id)->first();
                                if ($parent != null) {
                                    $ems_student = Student::where('crm_id', data_get($student, 'id'))->first();
                                    if ($ems_student != null) {
                                        $count++;
                                        $str = '#' . $count . ' - STUDENT: ' . data_get($student, 'Deal_Name') . ' - PARENT CRM ID: ' . $account->id . '- EMS Name: ' . ($parent != null ? $parent->fullname : 'NULL');
                                        $this->info($str);
                                        $ems_student->parent_id = $parent->id;
                                        StudentParent::deleteByStudent($ems_student->id, $parent->id);
                                        StudentParent::insert(['student_id' => $ems_student->id, 'parent_id' => $parent->id]);
                                        $ems_student->save();
                                    }
                                }
                            }
                            break;
                        }
                    }
                }
            }
        }

    }

    public function get_ebay_UTC_8601(DateTime $time)
    {
        $t = clone $time;
        $t->setTimezone(new DateTimeZone("Asia/Saigon"));
        return $t->format("Y-m-d H:i:s");
    }
}
