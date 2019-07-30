<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean syncronize classes error';


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
        $right_class = [];
        $old_crm_id = '';
        $classes =  DB::table('classes')->select('id','name','crm_id')->orderBy('crm_id', 'id')->get()->toArray();

        foreach($classes as $key => $class) {
            if ($class->crm_id !== $old_crm_id) {
                $old_crm_id = $class->crm_id;
                $right_class[$class->crm_id] = ['right' => $class->id, 'wrong' => []];
            }
        }

        foreach($right_class as $crm_id => $data) {
            foreach($classes as $key => $class) {
                if ((int)$class->crm_id === $crm_id && $class->id !== $data['right']) {
                    $right_class[$crm_id]['wrong'][] = $class->id;
                }
            }
        }

        foreach($right_class as $crm_id => $data) {
            foreach($this->related_tables as $table => $fkey) {
                $result = DB::table($table)->whereIn($fkey, $data['wrong'])->update([$fkey => $data['right']]);
                dump($result);
            }
        }

        foreach($right_class as $crm_id => $data) {
            $result = DB::table('classes')->whereIn('id', $data['wrong'])->delete();
        }
    }
}
