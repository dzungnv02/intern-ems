<?php
/**
 * @package     Product
 * @author      mAm <mamreezaa@gmail.com>
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bschmitt\Amqp\Facades\Amqp;

/**
 * Class RouteList
 * @package App\Console\Commands
 */
class SyncJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ems:crm-sync';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize from Zoho CRM.';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Syncing");
        Amqp::consume('ems_zohocrm_sync', function ($message, $resolver) {
    		
            var_dump($message->body);
         
            $resolver->acknowledge($message);
                 
         });
    }
}