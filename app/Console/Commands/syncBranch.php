<?php

namespace App\Console\Commands;

use App\Classes\ZohoCrmConnect;
use Illuminate\Console\Command;
use \App\Branch;
use function GuzzleHttp\json_encode;

class syncBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:branch {--pushlist} {--pull_branch} {--owner=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync branch to Zoho CRM';

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
        $option = $this->option('pushlist');

        $fillter_owner_id = $this->option('owner').'';

        $this->pull_branch($fillter_owner_id);

        return;

        $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_BRANCH');
        $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_BRANCH');

        try {
            if ($option) {
                $zoho_crm = new ZohoCrmConnect();
                $crm_data = ['data' => []];
                $branchs = Branch::getBranchs();
                
                if (is_array($branchs)) {
                    $count = 0;
                    foreach ($branchs as $branch) {
                        foreach ($crm_mapping as $key => $field) {
                            if ($key == 'EMS_ID') {
                                $crm_data['data'][$count][$key] = '' . $branch->id;
                            } else if ($key == 'EMS_SYNC_TIME') {
                                $crm_data['data'][$count][$key] = date('Y-m-d H:i:s');
                            } else if ($key == 'id' || $key == 'Owner') {
                                continue;
                            } else if ($field != '') {
                                $crm_data['data'][$count][$key] = $branch->$field;
                            } else {
                                $crm_data['data'][$count][$key] = '';
                            }
                        }
                        $count++;
                    }

                    $result = $zoho_crm->upsertRecord($crm_module, $crm_data);

                    $list = $zoho_crm->getAllRecords($crm_module);
                    
                    if (is_array($list) && count($list) > 0) {
                        foreach ($list as $crm_branch) {
                            $now = date('Y-m-d H:i:s');
                            $old_branch = Branch::find($crm_branch->EMS_ID);
                            if (is_object($old_branch)) {
                                $old_branch->crm_id = $crm_branch->id;
                                $old_branch->update();
                                $this->info('inserted ' . $old_branch->branch_name);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->info('ERROR: ' . $e->getMessage());
        }
    }

    protected function pull_branch($owner_id)
    {
        $zoho_crm = new ZohoCrmConnect();
        $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_BRANCH');
        $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_BRANCH');
        $ems_list = Branch::all()->toArray();
        $ems_fields = [];
        $crm_fields = [];

        foreach ($crm_mapping as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        $insert_list = [];
        $update_list = [];

        $fillter_field = $owner_id != '' ? 'Owner.id' : '';

        $criteria = $owner_id != '' ? '(Owner.id:equals:' . $owner_id . ')' : '';
        $crm_list = $zoho_crm->search($crm_module, '', '', $criteria);

        $zoho_crm->sync($crm_list, $ems_list, $insert_list, $update_list, $fillter_field, $owner_id);

        $this->info(json_encode($insert_list,JSON_UNESCAPED_UNICODE));
        $this->info(json_encode($update_list,JSON_UNESCAPED_UNICODE));

        if (count($insert_list) > 0) {
            foreach ($insert_list as $branch) {
                $new_branch = new Branch;
                for ($i = 0; $i < count($ems_fields); $i++) {
                    $ems_field = $ems_fields[$i];
                    $crm_field = $crm_fields[$i];
                    $value = $crm_field != 'Owner' ? $branch->$crm_field : $branch->Owner->name;
                    $new_branch->$ems_field = $value;
                }
                $new_branch->crm_owner = $branch->Owner->id;
                $new_branch->leader = 0;
                $new_branch->save();
            }
        }
    }
}
