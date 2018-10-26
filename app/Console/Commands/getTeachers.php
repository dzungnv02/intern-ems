<?php

namespace App\Console\Commands;

use App\Classes\ZohoCrmConnect;
use App\Teacher;
use Illuminate\Console\Command;

class getTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zohocrm:teacher {--getlist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from ZohoCRM';

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
        $option = $this->option('getlist');
        if ($option) {
            $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_TEACHER');
            $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_TEACHER');
            $zoho_crm = new ZohoCrmConnect();
            $list = $zoho_crm->getAllRecords($crm_module);

            $update_list = [];
            $insert_list = [];
            $update_ems_by_email = [];
            $update_sync_status_crm = ['data' => []];

            if (is_array($list) && count($list) > 0) {
                foreach ($list as $teacher) {
                    if ($teacher->EMS_ID !== null) {
                        array_push($update_list, $teacher);
                    } else {
                        array_push($insert_list, $teacher);
                    }
                }
            }

            if (count($insert_list) > 0) {
                $data = [];
                foreach ($insert_list as $teacher) {
                    $old_teacher = Teacher::getTeacher($teacher->Email, 'email');
                    if (count($old_teacher) == 0) {
                        $now = date('Y-m-d H:i:s');
                        $data = [
                            'name' => $teacher->Name,
                            'email' => $teacher->Email,
                            'nationality' => $teacher->Qu_c_t_ch,
                            'mobile' => $teacher->Phone,
                            'created_by' => $teacher->Owner->name,
                            'created_at' => $now,
                            'crm_id' => $teacher->id,
                        ];
                        $id = Teacher::insert($data);
                        
                        array_push($update_sync_status_crm['data'], [
                            'id' => $teacher->id,
                            'EMS_ID' => ''.$id,
                            'EMS_SYNC_TIME' => $now
                        ]);
                    }
                }
            }

            if (count($update_list) > 0) {
                $data = [];
                foreach ($update_list as $teacher) {
                    $now = date('Y-m-d H:i:s');
                    $old_teacher = Teacher::find($teacher->EMS_ID);
                    if (is_object($old_teacher)) {
                        $old_teacher->name = $teacher->Name;
                        $old_teacher->email = $teacher->Email;
                        $old_teacher->nationality = $teacher->Qu_c_t_ch;
                        $old_teacher->mobile = $teacher->Phone;
                        $old_teacher->updated_at = $now;
                        $old_teacher->crm_id = $teacher->id;
                        $old_teacher->update();
    
                        array_push($update_sync_status_crm['data'], [
                            'id' => $teacher->id,
                            'EMS_SYNC_TIME' => $now
                        ]);
                    }
                }
            }

            if (count($update_sync_status_crm['data']) > 0) {
                $zoho_crm->upsertRecord($crm_module, $update_sync_status_crm);
            }
        }
    }
}
