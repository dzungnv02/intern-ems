<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Classes\ZohoCrmConnect;
use \App\Parents;
use \App\Student;
use \App\Branch;

class syncParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:parent {--getlist} {--getchild} {--owner=}';
    //php artisan zoho:parent --getlist --owner=2666159000000213025

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

        $get_list = $this->option('getlist');
        $get_child= $this->option('getchild');

        $fillter_owner_id = $this->option('owner').'';
        if ($get_list) {
            $this->get_list($fillter_owner_id);
        }
        else if ($get_child) {
            $this->get_student($fillter_owner_id);
        }
    }

    protected function get_list($fillter_owner_id)
    {
        $ems_list = Parents::all()->toArray();
        $fillter_field = $fillter_owner_id != '' ? 'Owner.id' : '';

        $crm_module = config('zoho.MODULES.ZOHO_MODULE_PARENTS');

        $ems_fields = [];
        $crm_fields = [];
        $insert_list = [];
        $update_list = [];
        $mapping_fields = config('zoho.MAPPING.ZOHO_MODULE_PARENTS');
        foreach ($mapping_fields as $crm_field => $ems_field) {
            array_push($ems_fields, $ems_field);
            array_push($crm_fields, $crm_field);
        }

        $this->info('Start sync module: ' . $crm_module);

        $zoho_crm = new ZohoCrmConnect();
        $crm_list = $zoho_crm->getAllRecords($crm_module);

        $zoho_crm->sync($crm_list, $ems_list, $insert_list, $update_list, $fillter_field, $fillter_owner_id);

        if (count($insert_list) > 0) {
            $data = [];
            foreach ($insert_list as $parent) {
                $new_parent = new Parents;
                $branch = Branch::getBranchByCrmOwner($parent->Owner->id);
                for ($i = 0; $i < count($ems_fields); $i++) {
                    $ems_field = $ems_fields[$i];
                    $crm_field = $crm_fields[$i];
                    $value = $crm_field != 'Owner' ? $parent->$crm_field : json_encode($parent->$crm_field, JSON_UNESCAPED_UNICODE);
                    $new_parent->$ems_field = $value;
                }
                $new_parent->branch_id = $branch->id;
                $new_parent->save();
            }
        }
        $this->info(count($insert_list) . ' record(s) inserted.');


        if (count($update_list) > 0) {
            $data = [];
            foreach ($update_list as $ems_id => $parent) {
                $old_parent = Parents::find($ems_id);
                $branch = Branch::getBranchByCrmOwner($parent->Owner->id);
                for ($i = 0; $i < count($ems_fields); $i++) {
                    $ems_field = $ems_fields[$i];
                    $crm_field = $crm_fields[$i];
                    $value = $crm_field != 'Owner' ? $parent->$crm_field : json_encode($parent->$crm_field, JSON_UNESCAPED_UNICODE);
                    $old_parent->$ems_field = $value;
                }
                $old_parent->branch_id = $branch->id;
                $old_parent->save();
            }
        }

        $this->info(count($update_list) . ' record(s) updated.');

    }
}
