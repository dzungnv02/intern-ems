<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InvoiceFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:tranfer-staff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer invoice to co-branch staff';


    protected $related_tables = [
        'assessments' => 'trial_class_id',
        'exams' => 'class_id',
        'rev_n_exp' => 'class_id',
        'student_classes' => 'class_id',
        'teacher_schedules' => 'class_id',
        'timetables' => 'class_id'
    ];

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
        $this->transfer();
    }

    protected function transfer() {

        $branchCodes = DB::table('branch')->select('id','branch_code')->where('branch_code', '!=', "")->get();

        foreach($branchCodes as $branch) {

            // $branchCode->branch_code;
            $invoiceBranchs = DB::table('rev_n_exp')->select('id')
                                ->where('invoice_number', 'like', "%".$branch->branch_code)
                                ->whereNotIn('created_by', function ($query) {
                                    $query->select('id')->from('staffs');
                                })
                                ->get()
                                ->toArray();
            $headOfBranch = DB::table('staffs')->select('id')->where('branch_id', $branch->id)->limit(1)->get()->toArray();
            if (count($invoiceBranchs)) {
                foreach($invoiceBranchs as $invoice) {
                    var_dump('Invoice '. $invoice->id . ' transfer to '. $headOfBranch[0]->id);
                    DB::table('rev_n_exp')
                    ->where('id', $invoice->id)
                    ->update([
                        'created_by' => $headOfBranch[0]->id
                    ]);
                }
            }

        }

    }
}
