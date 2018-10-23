<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RollCall extends Model
{
	protected $table = 'roll_calls';
	protected $fillable = ['status','timetable_id','time_call','note','student_id','class_id','created_at','updated_at'];

	/**
     * Lấy ra danh sách điểm danh các học sinh của lớp.
     *
     * @param  int $timetable_id
     * @return $studentOfClass
     */
    public static function getListRollCall($timetable_id)
	{
        $studentOfClass =DB::table('timetables')
        			->join('classes','classes.id','=','timetables.class_id')
                    ->join('student_classes','student_classes.class_id','classes.id')
                    ->join('students','students.id','=','student_classes.student_id')
                    ->leftJoin('roll_calls',function($join){
                            $join->on('roll_calls.timetable_id', '=', 'timetables.id');
                            $join->on('roll_calls.student_id', '=', 'student_classes.student_id');})
                    ->select('students.id','students.student_code','students.name','classes.name as class_name','roll_calls.status','roll_calls.note','roll_calls.id as rol_id')
                    ->where('timetables.id',$timetable_id)->get();
	    return $studentOfClass;
	}

    /**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return $roll_call
     */
    public static function store1($data){
        $roll_call = DB::table('roll_calls')->insert([
            ['status' => $data['status'],
                'student_id' => $data['student_id'],
                'timetable_id' => $data['timetable_id'],
                'time_call' => $data['time_call'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ],
        ]);
        return $roll_call;
    }

    /**
     * update note.
     *
     * @param  array $data
     * @return $roll_call
     */
    public static function updateNote($data,$id){
        $roll_call = DB::table('roll_calls')->where('id',$id)->update([
                        'note' => $data['note'],
                    ]);
        return $roll_call;
    }
}