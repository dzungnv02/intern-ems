<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TimeTable extends Model
{
    protected $table = 'timetables';
    protected $fillable = ['week_day','date','week_day','start','finish', 'teacher_id','time','class_id','created_at','updated_at'];

    /**
     * Hiển thị danh sách thời khóa biểu.
     *
     * @param int $id
     * @return $time_table
     */
    public static function get_list_time_table($class_id){
        $time_table = DB::table('timetables')
                ->join('teachers','teachers.id','=','timetables.teacher_id')
                ->where('timetables.class_id', $class_id)
                ->select('timetables.*', 'teachers.name as teacher_name')->get()->toArray();
    	return $time_table;
    }

    public static function insert ($data) {
        return DB::table('timetables')->insert($data);
    }

    public static function update_by_class ($class_id, $date, $data) {
        return DB::table('timetables')
                ->where('class_id', $class_id)
                ->where('date', $date)
                ->update($data);
    }

    public static function get_time_table_by_class($class_id) {
        return DB::table('timetables')->select('*')->where('class_id', $class_id)->get()->toArray();
    }

    /**
     * EDIT thời khóa biểu.
     *
     * @param  int $id
     * @return $timetable
     */
    public static function edit($id){
        $timetable = DB::table('timetables')->where('id',$id)->get();
        return $timetable;
    }
    /**
     * Update thông tin giáo viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  array  $data
     * @return void
     */
    public static function update1($data, $id){
        $timetable = DB::table('timetables')->where('id', $id)
                        ->update(['date' => $data['date'],
                                'time' => $data['time'],
                                'week_days' => $data['week_days'],
                                'created_at' => $data['created_at'],
                                'updated_at' => $data['updated_at'],
                            ]);
        return $timetable;
    }
}
