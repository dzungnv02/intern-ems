<?php

namespace App\Classes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Misc
{
    public static function invoice_of_student($student_id, $inv_type = 1)
    {

        $query = DB::table('rev_n_exp as rexp')
                ->join('students as std', 'rexp.student_id','std.id')
                ->join('classes as cls', 'cls.id', 'rexp.class_id')
                ->join('staffs as stf', 'stf.id', 'rexp.created_by')
                ->join('branch as bra', 'bra.id', 'stf.branch_id')
                ->select(   'rexp.id as inv_id',
                            'rexp.invoice_number as inv_num',
                            'cls.name as class_name',
                            'rexp.start_date', 'rexp.end_date',
                            'rexp.duration','stf.name as cashier',
                            'bra.branch_name','rexp.amount',
                            'rexp.reason','rexp.type',
                            'rexp.created_at'
                        )
                ->where('std.id',$student_id)
                ->where('rexp.type', $inv_type)
                ->orderBy('created_at', 'DESC')
                ->get();

        return $query;

    }
}
