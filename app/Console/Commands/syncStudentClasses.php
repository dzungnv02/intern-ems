<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Classes;
use \App\Classes\ZohoCrmConnect;
use \App\StudentClass;

class syncStudentClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:studentclasses {--student}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

        $student = $this->option('student');
        $insert_list = [];
        $update_list = [];
        $update_sync_status_crm = ['data' => []];

        if ($student) {
            $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
            $crm_related = 'Deals';

            $zoho_crm = new ZohoCrmConnect();

            $classes_list = Classes::all()->toArray();
            if (count($classes_list) > 0) {
                foreach ($classes_list as $class) {
                    $students = $zoho_crm->getRelatedList($crm_module, $class['crm_id'], $crm_related);
                    if ($students !== false) {
                        foreach ($students as $crm_student) {
                            if ($crm_student->EMS_ID !== null) {
                                $update_list[$class['id']][] = $crm_student;
                            } else {
                                $insert_list[$class['id']][] = $crm_student;
                            }
                        }
                    }
                }

                foreach ($update_list as $class_id => $crm_students) {
                    foreach ($crm_students as $student) {
                        StudentClass::assignClass($class_id, $student->EMS_ID);
                    }
                }

                $data_mapping = [
                    'name' => 'H_t_n_con',
                    'email' => 'Email',
                    'student_code' => 'M_h_c_sinh',
                    'birthday' => 'Ng_y_sinh_con',
                    'birthyear' => 'N_m_sinh_con',
                    'crm_id' => 'id',
                    'parent_crm_id' => 'Contact_Name',
                    'crm_branch' => 'Owner',
                    'crm_class' => 'L_p_EMS',
                    'created_at' => '',
                ];

                foreach ($insert_list as $class_id => $crm_students) {
                    foreach ($crm_students as $student) {
                        $data = [];
                        $now = date('Y-m-d H:i:s');
                        foreach ($data_mapping as $field => $crm_field) {
                            if ($field == 'created_at') {
                                $data[$field] = $now;
                            } else if ($field == 'birthyear') {
                                if ($student->$crm_field != null && !is_numeric($student->$crm_field)) {
                                    $data[$field] = date('Y', strtotime($student->$crm_field));
                                } else {
                                    $data[$field] = $student->$crm_field;
                                }
                            } else if ($field == 'crm_branch' || $field == 'parent_crm_id') {
                                $data[$field] = $student->$crm_field != null ? json_encode($student->$crm_field, JSON_UNESCAPED_UNICODE) : $student->$crm_field;
                            } else {
                                $data[$field] = $student->$crm_field;
                            }
                        }

                        $data['name'] = is_null($data['name']) ? $student->Deal_Name : $data['name'];
                        $id = Student::insert($data);

                        array_push($update_sync_status_crm['data'], [
                            'id' => $student->id,
                            'EMS_ID' => '' . $id,
                            'EMS_SYNC_TIME' => $now,
                        ]);

                        StudentClass::assignClass($class_id, $id);
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
}
