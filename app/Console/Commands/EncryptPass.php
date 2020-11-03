<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EncryptPass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypt:passwd {--str=}';

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

        $passwd = $this->option('str');
        if (!$passwd ) {
            $passwd = '123456789';
        }
        
        $encrypt_pwd = bcrypt($passwd);
        $this->info('Origin password: '. $passwd);
        $this->info('Encrypted password: '. $encrypt_pwd);
    }
}
