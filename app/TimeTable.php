<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TimeTable extends Model
{
    protected $table = 'timetables';
    protected $fillable = ['week_days','date','time','class_id','created_at','updated_at'];

    /**
     * Hiển thị danh sách thời khóa biểu.
     *
     * @param int $id
     * @return $time_table
     */
    public static function getListTimeTable($id){
    	$time_table = DB::table('timetables')->join('classes','classes.id','=','timetables.class_id')
        ->where('timetables.class_id',$id)->select('classes.duration','timetables.*')->get();
    	return $time_table;
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
