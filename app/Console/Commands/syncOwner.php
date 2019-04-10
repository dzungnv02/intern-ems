<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Classes\ZohoCrmConnect;
use \App\Branch;

class syncOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:owner {--syncbranch} {--owner=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get users from ZohoCRM';

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
        //php artisan zohocrm:owner --syncbranch
        $defaultTimeZone = 'Asia/Saigon';
        if(date_default_timezone_get() != $defaultTimeZone) {
            date_default_timezone_set($defaultTimeZone);
        }

        $zoho_crm = new ZohoCrmConnect();
        $users = $zoho_crm->getUsers();

        $option = $this->option('syncbranch');
        if ($option) {
            if (is_array($users)) {
                foreach ($users as $user_crm) {
                    $result = Branch::getBranchByEmail($user_crm->email);
                    $branch = count($result) > 0 ?  Branch::find($result[0]->id) : null;
                    if ($branch != null) {
                        $branch->crm_owner = $user_crm->id;
                        $branch->crm_owner_name = $user_crm->full_name;
                        $branch->update();
                    }
                }
            }
        }
    }
}
