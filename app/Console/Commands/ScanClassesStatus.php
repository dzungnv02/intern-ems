<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use \App\Classes\ZohoCrmConnect;
use \App\CrmServices\ClassesSync;

class ScanClassesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:classes';

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

        $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
        $zoho_crm = new ZohoCrmConnect();
        $exist_classes = [];
        $new_classes = [];
        $delete_classes = [];
        $crm_classes = [];
        $crm_actived_classes = $zoho_crm->search($crm_module, '', '', '(Product_Active:equals:true)');
        $crm_inactived_classes = $zoho_crm->search($crm_module, '', '', '(Product_Active:equals:false)');
        if ($crm_actived_classes && $crm_inactived_classes) {
            $crm_classes = array_merge($crm_actived_classes, $crm_inactived_classes);
        } else if ($crm_actived_classes && !$crm_inactived_classes) {
            $crm_classes = $crm_actived_classes;
        } else if (!$crm_actived_classes && $crm_inactived_classes) {
            $crm_classes = $crm_inactived_classes;
        }

        $ems_classes = DB::table('classes')->select('*')->get();
        if ($crm_classes && $ems_classes) {
            $this->info(count($crm_classes) . ' classes on CRM');

            $ems_cls = $ems_classes->toArray();
            foreach ($crm_classes as $crm_class) {
                $crm_id = $crm_class->id;
                foreach ($ems_cls as $cls) {
                    if ($cls->crm_id == $crm_id) {
                        array_push($exist_classes, $crm_id);
                    }
                }
            }

            foreach ($crm_classes as $crm_class) {
                $crm_id = $crm_class->id;
                if (!in_array($crm_id, $exist_classes)) {
                    array_push($new_classes, $crm_id);
                }
            }

            foreach ($ems_cls as $cls) {
                $crm_id = $cls->crm_id;
                if (!in_array($crm_id, $exist_classes) && !in_array($crm_id, $new_classes)) {
                    array_push($delete_classes, $crm_id);
                }
            }
            
            $now = date('Y-m-d');
            foreach ($crm_classes as $crm_class) {
                $crm_id = $crm_class->id;
                $e_cls = null;
                if (!in_array($crm_id, $new_classes)) {
                    foreach ($ems_cls as $cls) {
                        if ($cls->crm_id == $crm_id) {
                            $e_cls = $cls;
                            break;
                        }
                    }
                }

                if (in_array($crm_id, $exist_classes)) {
                    $start_date = data_get($crm_class, 'start_date');
                    $val = data_get($crm_class, 'Product_Active');
                    $status = 0;
                    if ($val == true && $start_date <= $now) {
                        $status = 2;
                    } else if ($val == true && $start_date > $now) {
                        $status = 1;
                    } else {
                        $status = 3;
                    }
                    DB::table('classes')->where('id', $e_cls->id)->update(['status' => $status, 'start_date' => $start_date]);

                }  else if (in_array($crm_id, $new_classes)) {
                    $ClassService = new ClassesSync;
                    $ClassService->add_class($crm_id);
                }
            }

            // foreach($ems_cls as $cls) {
            //     if (in_array($cls->crm_id, $delete_classes)) {
            //         DB::table('student_classes')->where('class_id', $cls->id)->delete();
            //         DB::table('classes')->where('id', $cls->id)->delete();
            //     }
            // }

        }
    }
}
