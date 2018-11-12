<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use \App\Classes\ZohoCrmConnect;
use \App\Student;
use \App\Branch;
use \App\Classes;

class syncStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:student {--getlist} {--syncbranch} {--syncclass}';

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
        $zoho_crm = new ZohoCrmConnect();

        $this->get_list($zoho_crm);

        //php artisan zoho:student --syncbranch
        $syncBranch = $this->option('syncbranch');
        if ($syncBranch) {
            $branchs = Branch::all(['id', 'crm_id', 'crm_owner_id'])->toArray();
            $students = Student::all()->toArray();

            foreach($students as $student) {
                $crm_branch = $student['crm_branch'] ? json_decode($student['crm_branch'])->id : null;
                
                if ($crm_branch != null ) {
                    foreach ($branchs as $branch) {
                        if ($crm_branch == $branch['crm_owner_id']) {
                            $student['branch_id'] = $branch['id'];
                            break;
                        }
                    }
                    if ($student['branch_id']) {
                        $objStudent = Student::find($student['id']);
                        $objStudent->branch_id = $student['branch_id'];
                        $objStudent->update();
                    }
                }
            }
        }

        $syncClass = $this->option('syncclass');
        if ($syncClass) {

        }
    }

    protected function get_list($zoho_crm) 
    {
        $getlist = $this->option('getlist');
        if ($getlist) {
            $crm_module = config('zoho.MODULES.ZOHO_MODULE_STUDENTS');
            $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_STUDENTS');
            $this->info('Start sync module: ' . $crm_module);

            $list = $zoho_crm->getAllRecords($crm_module);
            
            if (!$list) {
                $this->info('Can not get any record!');
                exit();
            }

            $update_list = [];
            $insert_list = [];
            $update_ems_by_email = [];
            $update_sync_status_crm = ['data' => []];

            if (is_array($list) && count($list) > 0) {
                foreach ($list as $student) {
                    if ($student->EMS_ID !== null) {
                        array_push($update_list, $student);
                    } else {
                        array_push($insert_list, $student);
                    }
                }
            }

            $this->info('INSERT LIST: ' . count($insert_list));
            $this->info('UPDATE LIST: ' . count($update_list));

            if (count($insert_list) > 0) {
                $data_mapping = [
                    'name' => 'Deal_Name',
                    'email' => 'Email',
                    'student_code' => 'M_h_c_sinh',
                    'birthday' => 'Ng_y_sinh_con',
                    'birthyear' => 'N_m_sinh_con',
                    'crm_id' => 'id',
                    'parent_crm_id' => 'Contact_Name',
                    'crm_branch' => 'Owner',
                    'crm_class' => 'L_p_EMS',
                    'student_code' => 'M_h_c_sinh',
                    'created_at' => '',
                ];

                
                Log::info(json_encode($insert_list[0], JSON_UNESCAPED_UNICODE));
                
                foreach ($insert_list as $crm_student) {
                    $data = [];
                    $now = date('Y-m-d H:i:s');
                    foreach ($data_mapping as $field => $crm_field) {
                        if ($field == 'created_at') {
                            $data[$field] = $now;
                        } else if ($field == 'birthyear') {
                            if ($crm_student->Ng_y_sinh_con != null) {
                                $data[$field] = date('Y', strtotime($crm_student->Ng_y_sinh_con));
                            } else {
                                $data[$field] = is_numeric($crm_student->$crm_field) ? $crm_student->$crm_field : null;
                            }
                        } else if ($field == 'crm_branch' || $field == 'parent_crm_id' || $field == 'crm_class') {
                            $data[$field] = $crm_student->$crm_field != null ? json_encode($crm_student->$crm_field, JSON_UNESCAPED_UNICODE) : $crm_student->$crm_field;
                        } else {
                            $data[$field] = $crm_student->$crm_field;
                        }
                    }

                    $data['name'] = is_null($data['name']) ? $crm_student->Deal_Name : $data['name'];

                    $id = Student::insert($data);

                    array_push($update_sync_status_crm['data'], [
                        'id' => $student->id,
                        'EMS_ID' => '' . $id,
                        'EMS_SYNC_TIME' => $now,
                    ]);
                }
            }

            if (count($update_list) > 0) {
                $data_mapping = [
                    'name' => 'Deal_Name',
                    'email' => 'Email',
                    'student_code' => 'M_h_c_sinh',
                    'birthday' => 'Ng_y_sinh_con',
                    'birthyear' => 'N_m_sinh_con',
                    'crm_id' => 'id',
                    'parent_crm_id' => 'Contact_Name',
                    'crm_branch' => 'Owner',
                    'student_code' => 'M_h_c_sinh',
                    'updated_at' => '',
                ];

                foreach ($update_list as $crm_student) {
                    $data = [];
                    $now = date('Y-m-d H:i:s');
                    foreach ($data_mapping as $field => $crm_field) {
                        if ($field == 'updated_at') {
                            $data[$field] = $now;
                        } else if ($field == 'birthyear') {
                            if ($crm_student->Ng_y_sinh_con != null) {
                                $data[$field] = date('Y', strtotime($crm_student->Ng_y_sinh_con));
                            } else {
                                $data[$field] = is_numeric($crm_student->$crm_field) ? $crm_student->$crm_field : null;
                            }
                        } else if ($field == 'crm_branch' || $field == 'parent_crm_id' || $field == 'crm_class') {
                            $data[$field] = json_encode($crm_student->$crm_field, JSON_UNESCAPED_UNICODE);
                        }  else {
                            $data[$field] = $crm_student->$crm_field;
                        }
                    }

                    $old_student = Student::find($crm_student->EMS_ID);
                    
                    if (is_object($old_student)) {
                        foreach ($data as $field => $value) {
                            $old_student->$field = $value;
                        }
                        $old_student->update();
                        array_push($update_sync_status_crm['data'], [
                            'id' => $crm_student->id,
                            'EMS_SYNC_TIME' => $now,
                        ]);
                    }
                    else {
                        $data['id'] = $crm_student->EMS_ID;
                        Student::insert($data);
                    }
                }
            }

            if (count($update_sync_status_crm['data']) > 0) {
                $data = ['data' => []];

                $max_record = 100;
                $start_offset = 0;
                $total_page = count($update_sync_status_crm['data']) <= $max_record ? 1 : ceil(count($update_sync_status_crm['data']) / $max_record);
                for ($i = 1; $i <= $total_page; $i++) {
                    $ar = array_slice($update_sync_status_crm['data'], $start_offset, $max_record);
                    $data['data'] = $ar;

                    $this->info('UPDATE CRM: ' . count($data['data']));
                    $this->info('FROM: ' . $data['data'][0]['id'] . ' -- TO: ' . $data['data'][count($data['data']) - 1]['id']);

                    try {
                        $zoho_crm->upsertRecord($crm_module, $data);
                    } catch (Exception $e) {
                        Log::error($e->getMessage());
                        break;
                    }

                    $start_offset = $start_offset == 0 ? $max_record : $max_record * $i;

                }
            }
        }
    }
}
