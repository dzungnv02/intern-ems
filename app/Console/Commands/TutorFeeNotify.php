<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Notification;
use App\Staff;
use App\Invoice;
use App\Branch;

class TutorFeeNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tutorfee:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan and sent nofification tutorfee warning period';

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
        $invoices = $this->scan_invoice();
    }

    protected function scan_invoice() {
        $today = date('Y-m-d');
        $expired_before_interval = 7;
        $invoices = DB::table('rev_n_exp')
                    ->select('rev_n_exp.id', 'branch.id as branch_id', 'branch.branch_name', 'rev_n_exp.invoice_number', 'rev_n_exp.student_id', 'students.name as student_name', 'rev_n_exp.start_date', 'rev_n_exp.end_date')
                    ->join('students', 'students.id', '=', 'rev_n_exp.student_id')
                    ->join('staffs', 'staffs.id', '=', 'rev_n_exp.created_by')
                    ->join('branch','branch.id', '=', 'staffs.branch_id')
                    ->where('rev_n_exp.type', '=', 1)
                    ->havingRaw('DATEDIFF(rev_n_exp.end_date, ?) > 0', [$today])
                    ->havingRaw('DATEDIFF(rev_n_exp.end_date, ?) <= ?', [$today, $expired_before_interval])
                    ->get();
        return $invoices;
    }
}
