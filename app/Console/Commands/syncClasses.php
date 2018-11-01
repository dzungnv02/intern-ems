<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Classes\ZohoCrmConnect;
use \App\Teacher;
use \App\Branch;
use \App\Classes;

class syncClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:classes {--getlist}';

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

        $getlist = $this->option('getlist');
        if ($getlist) {
            $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
            $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_CLASS');
            $this->info('Start sync module: ' . $crm_module);

            $zoho_crm = new ZohoCrmConnect();
            $fields = 'id,EMS_ID';
            $crm_classes = $zoho_crm->getAllRecords($crm_module, $fields);

            $insert_list = [];
            $update_list = [];
            $update_sync_status_crm = ['data' => []];

            if (!$crm_classes) {
                $this->info('Can not get any record!');
                exit();
            }

            if (is_array($crm_classes)) {
                foreach ($crm_classes as $crm_class) {
                    if ($crm_class->EMS_ID !== null) {
                        array_push($update_list, $crm_class);
                    } else {
                        array_push($insert_list, $crm_class);
                    }
                }

                $this->info('INSERT ' . count($insert_list) . ' records');
                $this->info('UPDATE ' . count($update_list) . ' records');

                $branchs = Branch::all()->toArray();
                $teachers = Teacher::all()->toArray();

                if (count($insert_list)) {
                    $data_mapping = [
                        'name' => 'Product_Name',
                        'teacher_id' => '',
                        'branch_id' => '',
                        'status' => 'Product_Active',
                        'schedule' => '',
                        'crm_id' => 'id',
                        'crm_course' => 'Ch_ng_tr_nh_h_c',
                        'crm_teacher' => 'Gi_o_vi_n1',
                        'crm_schedule' => 'L_ch_h_c_trong_tu_n',
                        'crm_branch' => 'Owner',
                        'created_at' => '',
                    ];

                    foreach ($insert_list as $crm_class) {
                        $data = [];
                        $now = date('Y-m-d H:i:s');

                        $ar = $zoho_crm->getRecordById($crm_module, $crm_class->id);
                        $crm_class = $ar;

                        foreach ($data_mapping as $field => $crm_field) {
                            if ($field == 'created_at') {
                                $data[$field] = $now;
                            }
                            else if ($field == 'crm_branch' || $field == 'crm_teacher'){
                                $data[$field] = $crm_class->$crm_field != null ? json_encode($crm_class->$crm_field, JSON_UNESCAPED_UNICODE) : $crm_class->$crm_field;
                            }
                            else if ($field == 'branch_id') {
                                $owner_id = $crm_class->Owner->id;
                                foreach($branchs as $branch) {
                                    if ($branch['crm_owner_id'] == $owner_id)
                                    {
                                        $data[$field] = $branch['id'];
                                        break;
                                    }
                                }
                            } else if ($field == 'teacher_id' && !is_null($crm_class->Gi_o_vi_n1)) {
                                $crm_teacher_id = $crm_class->Gi_o_vi_n1->id;
                                foreach($teachers as $teacher) {
                                    if ($teacher['crm_id'] == $crm_teacher_id)
                                    {
                                        $data[$field] = $teacher['id'];
                                        break;
                                    }
                                }
                            } else if ($field == 'crm_schedule') {
                                $data[$field] = '';
                                if (property_exists($crm_class, 'L_ch_h_c_trong_tu_n')) {
                                    if (count($crm_class->L_ch_h_c_trong_tu_n) > 0) {
                                        $schedule = json_decode(json_encode($crm_class->L_ch_h_c_trong_tu_n[0], JSON_UNESCAPED_UNICODE), true);
                                        unset($schedule['$approval']);
                                        unset($schedule['$currency_symbol']);
                                        unset($schedule['$process_flow']);
                                        unset($schedule['$approved']);
                                        unset($schedule['$editable']);
                                        unset($schedule['id']);
                                        $data[$field] = json_encode($schedule, JSON_UNESCAPED_UNICODE); 
                                    }                                
                                }

                            } else {
                                if ($crm_field == '') continue;
                                $data[$field] = $crm_class->$crm_field;
                            }
                        }

                        $id = Classes::insertOne($data);

                        array_push($update_sync_status_crm['data'], [
                            'id' => $crm_class->id,
                            'EMS_ID' => '' . $id,
                            'EMS_SYNC_TIME' => $now,
                        ]);
                        
                        usleep(500);
                    }
                }

                if (count($update_list)) {
                    $data_mapping = [
                        'id' => 'EMS_ID',
                        'name' => 'Product_Name',
                        'teacher_id' => '',
                        'branch_id' => '',
                        'status' => 'Product_Active',
                        'schedule' => '',
                        'crm_id' => 'id',
                        'crm_course' => 'Ch_ng_tr_nh_h_c',
                        'crm_teacher' => 'Gi_o_vi_n1',
                        'crm_schedule' => 'L_ch_h_c_trong_tu_n',
                        'crm_branch' => 'Owner',
                        'updated_at' => '',
                    ];

                    foreach ($update_list as $crm_class) {
                        $data = [];
                        $now = date('Y-m-d H:i:s');

                        $ar = $zoho_crm->getRecordById($crm_module, $crm_class->id);
                        $crm_class = $ar;

                        foreach ($data_mapping as $field => $crm_field) {
                            if ($field == 'updated_at') {
                                $data[$field] = $now;
                            }
                            else if ($field == 'crm_branch' || $field == 'crm_teacher'){
                                $data[$field] = $crm_class->$crm_field != null ? json_encode($crm_class->$crm_field, JSON_UNESCAPED_UNICODE) : $crm_class->$crm_field;
                            }
                            else if ($field == 'branch_id') {
                                $owner_id = $crm_class->Owner->id;
                                foreach($branchs as $branch) {
                                    if ($branch['crm_owner_id'] == $owner_id)
                                    {
                                        $data[$field] = $branch['id'];
                                        break;
                                    }
                                }
                            } else if ($field == 'teacher_id' && !is_null($crm_class->Gi_o_vi_n1)) {
                                $crm_teacher_id = $crm_class->Gi_o_vi_n1->id;
                                foreach($teachers as $teacher) {
                                    if ($teacher['crm_id'] == $crm_teacher_id)
                                    {
                                        $data[$field] = $teacher['id'];
                                        break;
                                    }
                                }
                            } else if ($field == 'crm_schedule') {
                                $data[$field] = '';
                                if (property_exists($crm_class, 'L_ch_h_c_trong_tu_n')) {
                                    if (count($crm_class->L_ch_h_c_trong_tu_n) > 0) {
                                        $schedule = json_decode(json_encode($crm_class->L_ch_h_c_trong_tu_n[0], JSON_UNESCAPED_UNICODE), true);
                                        unset($schedule['$approval']);
                                        unset($schedule['$currency_symbol']);
                                        unset($schedule['$process_flow']);
                                        unset($schedule['$approved']);
                                        unset($schedule['$editable']);
                                        unset($schedule['id']);
                                        $data[$field] = json_encode($schedule, JSON_UNESCAPED_UNICODE); 
                                    }                                
                                }

                            } else if ($field == 'id') {
                                $id = $crm_class->$crm_field;
                            }
                            else {
                                if ($crm_field == '') continue;
                                $data[$field] = $crm_class->$crm_field;
                            }
                        }

                        $id = Classes::updateOne($id, $data);

                        array_push($update_sync_status_crm['data'], [
                            'id' => $crm_class->id,
                            'EMS_ID' => '' . $id,
                            'EMS_SYNC_TIME' => $now,
                        ]);
                        usleep(500);
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
