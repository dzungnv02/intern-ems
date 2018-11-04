<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Classes\ZohoCrmConnect;
use \App\Parents;
use \App\Branch;

class syncParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:parent {--getlist} {--syncbranch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $crm_module = config('zoho.MODULES.ZOHO_MODULE_PARENTS');
            $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_PARENTS');
            $this->info('Start sync module: ' . $crm_module);

            $zoho_crm = new ZohoCrmConnect();
            $crm_parents = $zoho_crm->getAllRecords($crm_module);

            $insert_list = [];
            $update_list = [];
            $update_sync_status_crm = ['data' => []];

            if (is_array($crm_parents)) {
                foreach ($crm_parents as $crm_parent) {
                    if ($crm_parent->EMS_ID !== null) {
                        array_push($update_list, $crm_parent);
                    } else {
                        array_push($insert_list, $crm_parent);
                    }
                }

                $this->info('INSERT ' . count($insert_list) . ' records');
                $this->info('UPDATE ' . count($update_list) . ' records');

                $branchs = Branch::all()->toArray();

                if (count($insert_list)) {
                    $data_mapping = [
                        'fullname' => 'Account_Name',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'parent_role' => 'Vai_tr_ph_huynh',
                        'facebook' => 'Facebook_c_nh_n',
                        'crm_id' => 'id',
                        'branch_id' => '',
                        'crm_branch' => 'Owner',
                        'created_at' => '',
                    ];

                    foreach ($insert_list as $crm_parent) {
                        $data = [];
                        $now = date('Y-m-d H:i:s');
                        foreach ($data_mapping as $field => $crm_field) {
                            if ($field == 'created_at') {
                                $data[$field] = $now;
                            }
                            else if ($field == 'crm_branch'){
                                $data[$field] = $crm_parent->$crm_field != null ? json_encode($crm_parent->$crm_field, JSON_UNESCAPED_UNICODE) : $student->$crm_field;
                            }
                            else if ($field == 'branch_id') {
                                $owner_id = $crm_parent->Owner->id;
                                foreach($branchs as $branch) {
                                    if ($branch['crm_owner_id'] == $owner_id)
                                    {
                                        $data[$field] = $branch['id'];
                                        break;
                                    }
                                }
                            }
                            else {
                                $data[$field] = $crm_parent->$crm_field;
                            }
                        }
                        $id = Parents::insertOne($data);

                        array_push($update_sync_status_crm['data'], [
                            'id' => $crm_parent->id,
                            'EMS_ID' => '' . $id,
                            'EMS_SYNC_TIME' => $now,
                        ]);
                    }
                }

                if (count($update_list)) {
                    $data_mapping = [
                        'id' => 'EMS_ID',
                        'fullname' => 'Account_Name',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'parent_role' => 'Vai_tr_ph_huynh',
                        'facebook' => 'Facebook_c_nh_n',
                        'crm_id' => 'id',
                        'branch_id' => '',
                        'crm_branch' => 'Owner',
                        'updated_at' => '',
                    ];

                    foreach ($update_list as $crm_parent) {
                        $data = [];
                        $now = date('Y-m-d H:i:s');
                        $id = 0;
                        foreach ($data_mapping as $field => $crm_field) {
                            if ($field == 'updated_at') {
                                $data[$field] = $now;
                            }
                            else if ($field == 'crm_branch'){
                                $data[$field] = $crm_parent->$crm_field != null ? json_encode($crm_parent->$crm_field, JSON_UNESCAPED_UNICODE) : $student->$crm_field;
                            }
                            else if ($field == 'branch_id') {
                                $owner_id = $crm_parent->Owner->id;
                                foreach($branchs as $branch) {
                                    if ($branch['crm_owner_id'] == $owner_id)
                                    {
                                        $data[$field] = $branch['id'];
                                        break;
                                    }
                                }
                            }
                            else if ($field == 'id') {
                                $id = $crm_parent->$crm_field;
                            }
                            else {
                                $data[$field] = $crm_parent->$crm_field;
                            }
                        }
                        $result = Parents::updateOne($id, $data);

                        array_push($update_sync_status_crm['data'], [
                            'id' => $crm_parent->id,
                            'EMS_SYNC_TIME' => $now,
                        ]);
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
