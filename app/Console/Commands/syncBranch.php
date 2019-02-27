<?php

namespace App\Console\Commands;

use App\Classes\ZohoCrmConnect;
use Illuminate\Console\Command;
use \App\Branch;

class syncBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:branch {--pushlist}';

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
        try {
            if ($option) {
                $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_BRANCH');
                $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_BRANCH');
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
}
