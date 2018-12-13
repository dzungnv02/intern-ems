<?php

namespace App\Console\Commands;

use App\Classes\ZohoCrmConnect;
use App\Teacher;
use App\Branch;
use Illuminate\Console\Command;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;

class getTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:teacher {--owner=} {--getlist}';
    //Teacher Yen Hoa
    //php artisan zoho:teacher --getlist --owner=2666159000000213025

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
        $fillter_owner_id = $this->option('owner').'';

        $fillter_field = $fillter_owner_id != '' ? 'Owner.id' : '';
        $ems_fields = [];
        $crm_fields = [];
        $insert_list = [];
        $update_list = [];

        $mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_EMS_TEACHER');
        foreach ($mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        if ($option) {
            $ems_list = Teacher::all()->toArray();

            $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_TEACHER');
            $zoho_crm = new ZohoCrmConnect();
            $crm_list = $zoho_crm->getAllRecords($crm_module);

            $zoho_crm->sync($crm_list, $ems_list, $insert_list, $update_list, $fillter_field, $fillter_owner_id);

            if (count($insert_list) > 0) {
                $data = [];
                foreach ($insert_list as $teacher) {
                    $new_teacher = new Teacher;
                    $branch = Branch::getBranchByCrmOwner($teacher->Owner->id);
                    for ($i = 0; $i < count($ems_fields); $i++) {
                        $ems_field = $ems_fields[$i];
                        $crm_field = $crm_fields[$i];
                        $value = $crm_field != 'Owner' ? $teacher->$crm_field : json_encode($teacher->$crm_field, JSON_UNESCAPED_UNICODE);
                        $new_teacher->$ems_field = $value;
                    }

                    $new_teacher->branch_id = $branch->id;
                    $new_teacher->save();
                }
            }

            $this->info(count($insert_list) . ' record(s) inserted.');

            if (count($update_list) > 0) {
                $data = [];
                foreach ($update_list as $ems_id => $teacher) {
                    $old_teacher = Teacher::find($ems_id);
                    $branch = Branch::getBranchByCrmOwner($teacher->Owner->id);
                    for ($i = 0; $i < count($ems_fields); $i++) {
                        $ems_field = $ems_fields[$i];
                        $crm_field = $crm_fields[$i];
                        $value = $crm_field != 'Owner' ? $teacher->$crm_field : json_encode($teacher->$crm_field, JSON_UNESCAPED_UNICODE);
                        $old_teacher->$ems_field = $value;
                    }
                    $old_teacher->branch_id = $branch->id;
                    $old_teacher->save();
                }
            }

            
            $this->info(count($update_list) . ' record(s) updated.');
        }
    }
}
